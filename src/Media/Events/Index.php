<?php

namespace Media\Events;

use FastD\Config\Config;
use FastD\Debug\Exceptions\ServerInternalErrorException;
use FastD\Framework\Events\TemplateEvent;
use FastD\Http\Request;
use Media\Std\ProviderInterface;

class Index extends TemplateEvent
{
    public function indexAction(Request $request)
    {
        try {
            $connection = $this->getParameters('media.provider.connection');
            $repository = $this->getParameters('media.provider.repository');
            $mediaRepository = $this->getConnection($connection)->getRepository($repository);
            if (!($mediaRepository instanceof ProviderInterface)) {
                throw new ServerInternalErrorException('Repository provider error.');
            }
            $medias = $mediaRepository->getMedia(
                $request->query->hasGet('page', 1),
                $request->query->hasGet('offset', null),
                $request->query->hasGet('limit', 15),
                $request->query->hasGet('last_id', null)
            );
        } catch (\Exception $e) {
            throw $e;
        }

        return $this->render('Media/Resources/views/media/list.twig', [
            'medias' => $medias,
            'upload_url' => $this->getParameters('media.uploaded.url')
        ]);
    }

    public function editorAction()
    {
        return $this->render('Media/Resources/views/editors/simple.twig', [
            'upload_url'        => $this->getParameters('media.uploaded.url'),
            'provider_remote'   => $this->getParameters('media.provider.remote_url'),
        ]);
    }
}