<?php
namespace PruneMazui\Tetrice\GameCore;

use PruneMazui\Tetrice\FrameProcessInterface;
use PruneMazui\Tetrice\Controller;
use PruneMazui\Tetrice\GameCore\Tetoriminone\AbstractTetoriminone;
use PruneMazui\Tetrice\GameCore\Tetoriminone\TetoriminoneFactory;

class GameManager implements FrameProcessInterface
{
    /**
     * @var Controller
     */
    private $controller;

    /**
     * @var Field
     */
    private $field;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var AbstractTetoriminone
     */
    private $tetoriminone;

    /**
     * @var TetoriminoneFactory
     */
    private $factory;

    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
        $this->field = new Field();
        $this->renderer = new Renderer();

        $this->factory = new TetoriminoneFactory($this->field, $controller);
    }

    /**
     * {@inheritDoc}
     * @see \PruneMazui\Tetrice\FrameProcessInterface::frameProcess()
     */
    public function frameProcess($mm_sec)
    {
        if (is_null($this->tetoriminone)) {
            $this->tetoriminone = $this->factory->create($mm_sec);
        }

        $this->tetoriminone->frameProcess($mm_sec);

        if ($this->tetoriminone->isLand()) {
            $this->field->land($this->tetoriminone);

            $this->tetoriminone = null;
        }

        $this->renderer->render($this->field, $this->tetoriminone);
    }
}
