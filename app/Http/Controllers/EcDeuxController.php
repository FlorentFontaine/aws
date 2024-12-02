<?php

namespace App\Http\Controllers;

use App\Http\Service\EcDeuxService;
use App\Http\Traits\AdminTrait;
use Aws\Ec2\Ec2Client;
use Aws\Exception\AwsException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class EcDeuxController extends Controller
{
    use AdminTrait;
    private EcDeuxService $ecDeuxService;
    private Ec2Client $ec2Client;

    public function __construct(EcDeuxService $ecDeuxService)
    {
        if ($this->isAdmin()){
            $this->ecDeuxService = $ecDeuxService;
            $this->ec2Client = $this->initializeEc2Client();
        } else {
            die();
        }
    }

    private function initializeEc2Client(): Ec2Client
    {
        return new Ec2Client([
            'credentials' => [
                'key'    => Config::get('services.aws.key'),
                'secret' => Config::get('services.aws.secret'),
            ],
            'region' => Config::get('services.aws.region'),
            'version' => 'latest',
        ]);
    }

    /**
     * @return Application|Factory|View|Response
     */
    public function ecDeuxInstance()
    {
        return $this->handleAwsRequest(function() {
            $result = $this->ec2Client->describeInstances();
            $sortedInstances = $this->ecDeuxService->sortInstancesByTags($result['Reservations']);

            return view('ecdeuxinstance', compact('sortedInstances'));
        });
    }


    /**
     * @return Application|Factory|View|Response
     */
    public function ecDeuxVolume()
    {
        return $this->handleAwsRequest(function() {
            $resultVolumes = $this->ec2Client->describeVolumes();
            $result = $this->ec2Client->describeInstances();

            $instances = $this->ecDeuxService->sortInstancesByTags($result['Reservations']);
            $volumes = $this->ecDeuxService->sortInstancesByTags($resultVolumes['Volumes']);

            return view('ecdeuxvolume', compact('volumes', 'instances'));
        });
    }

    /**
     * @param Request $request
     * @param string $instanceId
     * @return RedirectResponse
     */
    public function updateTagEcDeux(Request $request, string $instanceId): RedirectResponse
    {
        return $this->updateTags($request, $instanceId, 'instance');
    }

    /**
     * @param Request $request
     * @param string $volumeId
     * @return RedirectResponse
     */
    public function updateTagVolumeEcDeux(Request $request, string $volumeId): RedirectResponse
    {
        return $this->updateTags($request, $volumeId, 'volume');
    }

    /**
     *
     * @param callable $callback
     * @return Application|Factory|View|Response
     */
    private function handleAwsRequest(callable $callback)
    {
        try {
            return $callback();
        } catch (AwsException $e) {
            Log::error('AWS error: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }

    /**
     *
     * @param Request $request
     * @param string $resourceId
     * @param string $resourceType
     * @return RedirectResponse
     */
    private function updateTags(Request $request, string $resourceId, string $resourceType): RedirectResponse
    {
        try {
            $tags = $this->generateTagsFromRequest($request);

            $this->ec2Client->createTags([
                'Resources' => [$resourceId],
                'Tags' => $tags,
            ]);

            return redirect()->back()->with('success', "Le tag \"cicd-commentaire\" a été mis à jour avec succès.");
        } catch (AwsException $e) {
            Log::error('Erreur lors de la mise à jour des tags ' . $resourceType . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la mise à jour du tag.');
        }
    }

    /**
     *
     * @param Request $request
     * @return array
     */
    private function generateTagsFromRequest(Request $request): array
    {
        return [
            ['Key' => 'cicd-commentaire', 'Value' => $request->input('cicd-commentaire')],
            ['Key' => 'cicd-runmode', 'Value' => $request->input('cicd-runmode')],
            ['Key' => 'cicd-version', 'Value' => $request->input('cicd-version')],
            ['Key' => 'cicd-client', 'Value' => $request->input('cicd-client')],
            ['Key' => 'cicd-app', 'Value' => $request->input('cicd-app')],
        ];
    }
}
