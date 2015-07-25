<?php

namespace Media\Events;

use FastD\Framework\Events\TemplateEvent;

class Index extends TemplateEvent
{
    public function indexAction()
    {
        $mediaRepository = $this->getConnection('media')->getRepository('Media:Repository:Media');

        $medias = $mediaRepository->findAll();

        $this->dump($medias);

        return $this->render('Media/Resources/views/media/list.twig', [
            'medias' => $medias,
        ]);
    }
}