<?php

namespace ui;

use framework\core\Event;
use framework\web\ui\UIAlert;
use framework\web\ui\UIButton;
use framework\web\ui\UIHBox;
use framework\web\ui\UIImageView;
use framework\web\UIForm;

/**
 * Class MainForm
 * @package ui
 *
 * @path /
 *
 * @property UIHBox $pane
 * @property UIButton $button
 * @property UIImageView $image
 *
 */
class MainForm extends UIForm
{
    /**
     * @event button.click
     */
    public function doButtonClick()
    {
        // Создаем диалог типа confirm (вопросительный).
        $alert = new UIAlert('confirm');

        // Заголовок диалога.
        $alert->title = 'Вопрос';

        // Текст диалога.
        $alert->text = 'Вам есть 18 лет? (контент для взрослых)';

        // Определяем кнопки диалога.
        $alert->buttons = ['close' => 'Нет, отмена', 'yes' => 'Мне 18'];

        // Навешиваем на кнопку yes событие.
        $alert->on('action-yes', function () {
            // Показываем картинку, если ответили "Мне 18".
            $this->image->show();
        });

        // Показываем диалог.
        $alert->show();
    }

    /**
     * @event image.click
     */
    public function doImageClick()
    {
        $this->image->fadeOut(1000, function () {
            $this->image->hide();
        });
    }
}