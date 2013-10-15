<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A student
 *
 * @ORM\Entity()
 * @ORM\Table(name="movies")
 * @property integer $id
 */
class Movie
{

    /**
     *
     * @ORM\Column(type="string");
     * @var string
     */
    protected $description;

    /**
     * @ORM\Id
     * @ORM\Column(type="string");
     * @var string
     */
    protected $freebase_mid;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $title;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $original_title;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $genre;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $directed_by;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $starring;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $story_by;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $written_by;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $music_by;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $script_content;

    /**
     * Magic getter to expose protected properties.
     *
     * @param DateTime $property
     * @return mixed
     */
    public function __get($property)
    {
    	return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
    	$this->$property = $value;
    }
}
