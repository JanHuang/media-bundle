<?php

namespace Media\Events;

use FastD\Framework\Events\TemplateEvent;

class Index extends TemplateEvent
{
    public function indexAction()
    {
        return $this->render('Media/Resources/views/media/list.twig');
    }
}