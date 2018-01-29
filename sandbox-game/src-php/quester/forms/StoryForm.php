<?php
namespace quester\forms;

use framework\web\ui\UILabel;
use framework\web\ui\UIVBox;
use framework\web\UIForm;
use php\lib\fs;
use php\time\Timer;

/**
 * Class StoryForm
 * @package quester\forms
 *
 */
class StoryForm extends UIForm
{
    /**
     * @var array
     */
    private $story = [];

    /**
     * @var string
     */
    private $place;

    /**
     * @var array
     */
    private $objects = [];

    /**
     * @var string
     */
    private $state;

    /**
     * StoryForm constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->story = fs::parse('./src-php/story.yml');
    }

    protected function getFrmFormat()
    {
        return 'yml';
    }

    /**
     * @event navigate
     */
    public function doNavigate()
    {
    }

    /**
     * @event show
     */
    public function doShow()
    {
        $name = $this->story['name'];

        $box = new UIVBox();
        $box->align = ['center', 'center'];
        $box->height = '50%';
        $box->spacing = 0;
        $box->opacity = 0;
        $box->style = 'line-height: 28px;';

        $label = new UILabel($name);
        $label->font->size = 48;

        $box->add(new UILabel('История Первая'));
        $box->add($label);

        $this->layout->clear();
        $this->layout->add($box);

        $box->animate(['margin-top' => '-100px', 'opacity' => 1], ['duration' => '1s']);
    }
}