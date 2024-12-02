<?php

namespace App\Http\Controllers;

use App\Http\Service\RdsService;
use App\Http\Traits\AdminTrait;
use Aws\Exception\AwsException;
use Aws\Rds\RdsClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RdsController extends Controller
{
    use AdminTrait;

    public  $key;
    public  $secret;
    public  $region;
    private RdsService $rdsService;

    public function __construct(RdsService $rdsService)
    {
        if ($this->isAdmin()){
            $this->key = Config::get('services.aws.key');
            $this->secret = Config::get('services.aws.secret');
            $this->region = Config::get('services.aws.region');
            $this->rdsService = $rdsService;
        } else {
            die();
        }
    }

    /**
     * @return RdsClient
     */
    private function initializeRdsClient(): RdsClient
    {
        return new RdsClient([
            'credentials' => [
                'key'    => $this->key,
                'secret' => $this->secret,
            ],
            'region' => $this->region,
            'version' => 'latest',
        ]);
    }

    /**
     * @return Application|Factory|View
     */
    public function rds()
    {
        $rdsClient = $this->initializeRdsClient();

        try {
            $result = $rdsClient->describeDBInstances([]);
            $clusters = $rdsClient->describeDBClusters([]);
            $dataClusters = $this->rdsService->processClustersAndInstances($clusters, $result);

            return view('rds', compact('dataClusters'));

        } catch (AwsException $e) {
            // Handle exception, maybe return an error view
            return view('errors.aws_error', ['message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param string $identifier
     * @return RedirectResponse
     */
    public function updateTag(Request $request, string $identifier): RedirectResponse
    {
        $rdsClient = $this->initializeRdsClient();

        try {
            $rdsClient->addTagsToResource([
                'ResourceName' => $identifier,
                'Tags' => [
                    [
                        'Key' => 'cicd-commentaire',
                        'Value' => $request->input('cicd-commentaire')
                    ],
                    [
                        'Key' => 'cicd-runmode',
                        'Value' => $request->input('cicd-runmode')
                    ],
                    [
                        'Key' => 'cicd-version',
                        'Value' => $request->input('cicd-version')
                    ],
                    [
                        'Key' => 'cicd-client',
                        'Value' => $request->input('cicd-client')
                    ],
                    [
                        'Key' => 'cicd-app',
                        'Value' => $request->input('cicd-app')
                    ]
                ]
            ]);

            return redirect()->back()->with('success', 'Le tag "cicd-commentaire" a été mis à jour avec succès.');

        } catch (AwsException $e) {
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la mise à jour du tag.');
        }
    }
}

