<?php

namespace Media\Events;

use FastD\Config\Config;
use FastD\Framework\Events\TemplateEvent;

class Index extends TemplateEvent
{
    public function indexAction(Config $config)
    {
        $connection = $config->get('media.connection');
        $repository = $config->get('media.repository');
        $mediaRepository = $this->getConnection($connection)->getRepository($repository);
        $medias = $mediaRepository->findAll();

        return $this->render('Media/Resources/views/media/list.twig', [
            'medias' => $medias,
        ]);
    }

    public function editorAction()
    {
        return $this->render('Media/Resources/views/editors/simple.twig');
    }
}