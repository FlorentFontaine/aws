<?php
namespace App\Http\Controllers;

use App\Http\Service\ReportService;
use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    private CloudWatchLogsClient $cloudWatchLogsClient;
    private ReportService $reportService;

    /**
     * @param CloudWatchLogsClient $cloudWatchLogsClient
     */
    public function __construct(CloudWatchLogsClient $cloudWatchLogsClient, ReportService $reportService)
    {
        $this->reportService = $reportService;
        $this->cloudWatchLogsClient = $cloudWatchLogsClient;
    }

    /**
     * @param string|null $arn
     * @param string|null $identifier
     * @return Application|Factory|View
     */
    public function reportErreursLogs(string $arn = null, string $identifier = null)
    {
        if ($identifier && $arn){
            $logs = $this->getFatalLogsByArn($arn, $identifier);
            $parsedLogs = $this->reportService->parseLogs($logs);
            return view('report', ['logs' => $parsedLogs]);
        }else{
            return view('report');
        }

    }

    /**
     * @param string $arn
     * @param string $identifier
     * @return array
     */
    private function getFatalLogsByArn(string $arn, string $identifier): array
    {
        $logGroupNamePattern = $this->reportService->extractLogGroupNamePatternFromArn($arn);
        $logGroups = $this->listLogGroups($logGroupNamePattern);

        $logs = [];
        foreach ($logGroups as $logGroup) {
            $filteredLogs = $this->reportService->filterFatalLogs($logGroup['logGroupName'], $identifier);
            $logs = array_merge($logs, $filteredLogs);
        }

        return $logs;
    }

    /**
     * @param string $logGroupNamePattern
     * @return array
     */
    private function listLogGroups(string $logGroupNamePattern): array
    {
        $result = $this->cloudWatchLogsClient->describeLogGroups([
            'logGroupNamePrefix' => $logGroupNamePattern,
        ]);

        return $result['logGroups'] ?? [];
    }
}
