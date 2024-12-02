<?php

namespace App\Http\Service;

class RdsService
{
    public function __construct()
    {
    }

    /**
     * @param $clusters
     * @param $result
     * @return array
     */
    function processClustersAndInstances($clusters, $result): array
    {
        $dataClusters = [];

        // Traitement des clusters
        foreach ($clusters['DBClusters'] as $cluster) {
            $tagList = $cluster['TagList'];
            $tagList = array_combine(array_column($tagList, 'Key'), array_column($tagList, 'Value'));

            $dataClusters[$cluster['DBClusterIdentifier']] = [
                'CopyTagsToSnapshot' => $cluster['CopyTagsToSnapshot'],
                'Tags' => print_r($tagList, true),
                'cicd-client' => $tagList['cicd-client'] ?? null,
                'cicd-app' => $tagList['cicd-app'] ?? null,
                'cicd-version' => $tagList['cicd-version'] ?? null,
                'cicd-commentaire' => $tagList['cicd-commentaire'] ?? null,
                'cicd-runmode' => $tagList['cicd-runmode'] ?? null,
                'DBClusterParameterGroup' => $cluster['DBClusterParameterGroup'],
                'arn' => $cluster['DBClusterArn'],
                'Instance' => []
            ];
        }

        // Traitement des instances
        foreach ($result['DBInstances'] as $instance) {
            $tagList = $instance['TagList'];
            $tagList = array_combine(array_column($tagList, 'Key'), array_column($tagList, 'Value'));

            $dataClusters[$instance['DBClusterIdentifier']]['Instance'][$instance['DBInstanceIdentifier']] = [
                'Tags' => print_r($tagList, true),
                'cicd-client' => $tagList['cicd-client'] ?? null,
                'cicd-app' => $tagList['cicd-app'] ?? null,
                'cicd-version' => $tagList['cicd-version'] ?? null,
                'cicd-commentaire' => $tagList['cicd-commentaire'] ?? null,
                'cicd-runmode' => $tagList['cicd-runmode'] ?? null,
                'DBClusterParameterGroup' => $instance['DBParameterGroups'][0]['DBParameterGroupName'],
                'arn' => $instance['DBInstanceArn'],
                'DBInstanceClass' => $instance['DBInstanceClass']
            ];
        }

        return $dataClusters;
    }
}
