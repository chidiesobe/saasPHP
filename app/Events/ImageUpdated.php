<?php

namespace App\Events;


/**
 * Event: ImageUpdated
 *
 * Dispatched when an image associated with a model is updated.
 *
 * @package App\Events
 */
class ImageUpdated
{
    /**
     * The model instance associated with the image.
     *
     * @var mixed
     */
    public $model;

    /**
     * An array of updated image paths.
     *
     * @var array
     */
    public array $paths;

    /**
     * Create a new event instance.
     *
     * @param mixed $model The model that owns the updated image(s).
     * @param array $paths Array of updated image paths.
     */
    public function __construct($model, array $paths)
    {
        $this->model = $model;
        $this->paths = $paths;
    }
}
