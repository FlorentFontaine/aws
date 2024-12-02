<?php

namespace App\Http\Service;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Aws\Exception\AwsException;

class ReportService
{
    private CloudWatchLogsClient $cloudWatchLogsClient;

    public function __construct(CloudWatchLogsClient $cloudWatchLogsClient)
    {
        $this->cloudWatchLogsClient = $cloudWatchLogsClient;
    }

    /**
     * @param string $logGroupName
     * @param string $identifier
     * @return array
     */
    public function filterFatalLogs(string $logGroupName, string $identifier): array
    {
        $fatalPattern = 'Fatal';
        $errorPattern = 'error';
        $logs = [];

        try {
            // Obtenir le dernier log stream pertinent
            $latestLog = $this->getLatestLogStream($logGroupName, $identifier);

            if (!$latestLog) {
                return [];
            }

            // Variables pour la pagination
            $nextToken = null;
            $pageCount = 0;
            $maxPages = 30; // Limite de pages

            do {
                // Obtenir les événements de journalisation
                $params = [
                    'logGroupName'  => $logGroupName,
                    'logStreamName' => $latestLog["logStreamName"],
                    'startFromHead' => false,
                ];

                if ($nextToken) {
                    $params['nextToken'] = $nextToken;
                }

                $result = $this->cloudWatchLogsClient->getLogEvents($params);

                // Ajouter les événements de journalisation récupérés et filtrés
                foreach ($result['events'] as $event) {
                    if (strpos($event['message'], $errorPattern) !== false || strpos($event['message'], $fatalPattern) !== false) {
                        $logs[] = [
                            'timestamp' => $event['timestamp'],
                            'message'   => $event['message'],
                        ];
                    }
                }

                // Obtenir les tokens pour la prochaine page
                $nextToken = $result['nextBackwardToken'];
                $pageCount++;

            } while ($nextToken && count($result['events']) > 0 && $pageCount < $maxPages);

        } catch (AwsException $e) {
            error_log($e->getMessage());
        }

        return $logs;
    }

    /**
     * @param string $logGroupName
     * @param string $identifier
     * @return array|null
     */
    private function getLatestLogStream(string $logGroupName, string $identifier): ?array
    {
        $nextToken = null;
        $latestLog = null;
        $latestTimestamp = 0;
        $pageCount = 0;
        $maxPages = 4; // Limite de pages

        try {
            do {
                $params = [
                    'logGroupName' => $logGroupName,
                    'orderBy' => 'LastEventTime',
                    'descending' => true,
                ];

                if ($nextToken) {
                    $params['nextToken'] = $nextToken;
                }

                $filterResult = $this->cloudWatchLogsClient->describeLogStreams($params);

                foreach ($filterResult['logStreams'] as $logStream) {
                    if (substr((string)$logStream['logStreamName'], 0, strlen($identifier.'/www')) == $identifier.'/www') {
                        if ($logStream['lastEventTimestamp'] > $latestTimestamp) {
                            $latestLog = $logStream;
                            $latestTimestamp = $logStream['lastEventTimestamp'];
                        }
                    }
                }

                $nextToken = $filterResult['nextToken'] ?? null;
                $pageCount++;

            } while ($nextToken && count($filterResult['logStreams']) > 0 && $pageCount < $maxPages);
        } catch (AwsException $e) {
            error_log($e->getMessage());
        }

        return $latestLog ? [
            'logStreamName' => $latestLog['logStreamName'],
            'lastEventTimestamp' => $latestLog['lastEventTimestamp'],
        ] : null;
    }


    /**
     * @param array $logs
     * @return array
     */
    public function parseLogs(array $logs): array
    {
        $parsedLogs = [];
        foreach ($logs as $log) {
            $message = json_decode($log['message'], true);
            if ($message) {
                $parsedLogs[] = [
                    'logname' => $message['logname'] ?? 'N/A',
                    'date' => $message['date'] ?? 'N/A',
                    'function' => $message['function'] ?? 'N/A',
                    'pid' => $message['pid'] ?? 'N/A',
                    'message' => $message['message'] ?? 'N/A',
                    'referer' => $message['referer'] ?? 'N/A',
                ];
            }
        }
        return $parsedLogs;
    }


    /**
     * @param string $arn
     * @return string
     */
    public function extractLogGroupNamePatternFromArn(string $arn): string
    {
        $parts = explode(':', $arn);
        return $parts[6];
    }
}
