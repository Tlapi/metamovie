<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A student
 *
 * @ORM\Entity()
 * @ORM\Table(name="subtitles")
 * @property integer $id
 */
class Subtitles
{
    /**
     * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $mid;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $content;

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
