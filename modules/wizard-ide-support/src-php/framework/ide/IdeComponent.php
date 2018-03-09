<?php
namespace framework\ide;

use framework\core\Component;
use framework\localization\Localizer;
use php\graphic\Image;
use php\lib\reflect;
use php\lib\str;

/**
 * @package framework\ide
 *
 * @property string $name
 * @property string $localizedName
 * @property string $description
 * @property string $localizedDescription
 * @property Image $icon
 * @property string $className
 * @property bool $abstract
 * @property IdeComponent $parent
 * @property IdeComponentField[] $fields
 * @property string[] $events
 * @property Localizer $localizer
 */
class IdeComponent extends Component
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Image
     */
    private $icon;

    /**
     * @var bool
     */
    private $abstract;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $className;

    /**
     * @var IdeComponent|null
     */
    private $parent;

    /**
     * @var array
     */
    private $events = [];

    /**
     * @var Localizer
     */
    private $localizer;

    /**
     * @return string
     */
    protected function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    protected function setClassName(string $className)
    {
        $this->className = $className;
    }

    /**
     * @return IdeComponentField[]
     */
    protected function getFields(): array
    {
        return $this->components->findByClass(IdeComponentField::class);
    }

    /**
     * @return IdeComponent|null
     */
    protected function getParent(): ?IdeComponent
    {
        return $this->parent;
    }

    /**
     * @param IdeComponent|null $parent
     */
    protected function setParent(?IdeComponent $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    protected function getLocalizedName(): string
    {
        return $this->localizer ? $this->localizer->translate($this->name) : $this->name;
    }

    /**
     * @return string
     */
    protected function getLocalizedDescription(): string
    {
        return $this->localizer ? $this->localizer->translate($this->description) : $this->description;
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    protected function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    protected function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    protected function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    protected function isAbstract(): bool
    {
        return $this->abstract;
    }

    /**
     * @param bool $abstract
     */
    protected function setAbstract(bool $abstract)
    {
        $this->abstract = $abstract;
    }

    /**
     * @return string[]
     */
    protected function getEvents(): array
    {
        return $this->events;
    }

    /**
     * @return Localizer
     */
    protected function getLocalizer(): ?Localizer
    {
        return $this->localizer;
    }

    /**
     * @param Localizer $localizer
     */
    protected function setLocalizer(?Localizer $localizer)
    {
        $this->localizer = $localizer;
    }

    /**
     * @param string[] $events
     */
    protected function setEvents(array $events)
    {
        $this->events = $events;
    }

    /**
     * @return Image
     */
    protected function getIcon(): ?Image
    {
        return $this->icon;
    }

    /**
     * @param Image $icon
     */
    protected function setIcon(?Image $icon)
    {
        $this->icon = $icon;
    }


    /**
     * @param string $className
     * @return bool
     */
    public function isSubclassOf(string $className): bool
    {
        if ($className === $this->className) {
            return true;
        }

        return $this->parent && $this->parent->isSubclassOf($className);
    }
}