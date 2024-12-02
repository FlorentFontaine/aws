<?php

namespace App\Http\Service;

class EcDeuxService
{
    public function __construct()
    {
    }


    /**
     * @param array $reservations
     * @return array
     */
    function sortInstancesByTags(array $reservations): array
    {
        $sortedInstances = [];

        foreach ($reservations as $reservation) {
            if (isset($reservation['Instances']) && is_array($reservation['Instances'])) {
                foreach ($reservation['Instances'] as $instance) {
                    $sortedInstances[] = $this->processInstance($instance);
                }
            }

            if (isset($reservation['Tags']) && is_array($reservation['Tags'])) {
                    $sortedInstances[] = $this->processVolume( $reservation);
            }
        }

        return $sortedInstances;
    }

    function processInstance(array $instance): array
    {
        $tags = $instance['Tags'];
        $cicdClient = '';
        $cicdVersion = '';
        $cicdApp = '';
        $cicdCommentaire = '';
        $cicdRunmode = '';
        $name = $instance['InstanceId']; // Placeholder value for Name

        // Recherche des balises spécifiques
        foreach ($tags as $tag) {
            switch ($tag['Key']) {
                case 'Name':
                    $name = $tag['Value'];
                    break;
                case 'cicd-client':
                    $cicdClient = $tag['Value'];
                    break;
                case 'cicd-version':
                    $cicdVersion = $tag['Value'];
                    break;
                case 'cicd-app':
                    $cicdApp = $tag['Value'];
                    break;
                case 'cicd-commentaire':
                    $cicdCommentaire = $tag['Value'];
                    break;
                case 'cicd-runmode':
                    $cicdRunmode = $tag['Value'];
                    break;
                default:
                    // Autres cas de balises si nécessaire
                    break;
            }
        }

        return [
            'InstanceId' => $instance['InstanceId'],
            'InstanceType' => $instance['InstanceType'],
            'Name' => $name,
            'State' => $instance['State']['Name'],
            'Tags' => $tags,
            'cicd-client' => $cicdClient,
            'cicd-version' => $cicdVersion,
            'cicd-app' => $cicdApp,
            'cicd-commentaire' => $cicdCommentaire,
            'cicd-runmode' => $cicdRunmode
        ];
    }

    function processVolume(array $reservation): array
    {
        $tags = $reservation['Tags'];
        $cicdClient = '';
        $cicdVersion = '';
        $cicdApp = '';
        $cicdCommentaire = '';
        $cicdRunmode = '';
        $name = '';
        $volumeId = '';
        $instanceId = '';
        $instanceState = '';
        $size = '';

        foreach ($reservation["Attachments"] as $volume){
                $instanceId = $volume["InstanceId"];
                $volumeId = $volume["VolumeId"];
                $size = $reservation["Size"];
        }

        // Recherche des balises spécifiques
        foreach ($tags as $tag) {
            switch ($tag['Key']) {
                case 'Name':
                    $name = $tag['Value'];
                    break;
                case 'cicd-client':
                    $cicdClient = $tag['Value'];
                    break;
                case 'cicd-version':
                    $cicdVersion = $tag['Value'];
                    break;
                case 'cicd-app':
                    $cicdApp = $tag['Value'];
                    break;
                case 'cicd-commentaire':
                    $cicdCommentaire = $tag['Value'];
                    break;
                case 'cicd-runmode':
                    $cicdRunmode = $tag['Value'];
                    break;
                default:
                    // Autres cas de balises si nécessaire
                    break;
            }
        }

        return [
            'InstanceId' => $instanceId,
            'Size' => $size,
            'VolumeId' => $volumeId,
            'Name' => $name,
            'Tags' => $tags,
            'cicd-app' => $cicdApp,
            'cicd-client' => $cicdClient,
            'cicd-version' => $cicdVersion,
            'cicd-runmode' => $cicdRunmode,
            'cicd-commentaire' => $cicdCommentaire,
        ];
    }
}
