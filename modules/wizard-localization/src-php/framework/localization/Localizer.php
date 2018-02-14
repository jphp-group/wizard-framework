<?php
namespace framework\localization;

use framework\core\Component;
use framework\core\Event;
use framework\core\Logger;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;

/**
 * @package framework\localization
 *
 * @property string $language
 * @property string[] $languages
 */
class Localizer extends Component
{
    /**
     * @var array
     */
    private $messages = [];

    /**
     * @var string
     */
    private $language;

    /**
     * Localizer constructor.
     * @param string $language
     */
    public function __construct(string $language = null)
    {
        parent::__construct();

        $this->language = $language;
    }

    /**
     * @return string
     */
    protected function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    protected function setLanguage(?string $language)
    {
        $this->language = $language;
    }

    /**
     * @return array
     */
    protected function getLanguages(): array
    {
        return arr::keys($this->messages);
    }


    /**
     * @param string $message
     * @param array $args
     * @return string
     * @throws \Exception
     */
    public function translate(string $message, array $args = []): string
    {
        if (!$this->language) {
            throw new \Exception("Default Language is not set");
        }

        $message = $this->messages[$this->language][$message] ?? $message;

        foreach ($args as $key => $value) {
            $message = str::replace($message, "{{$key}}", $value);
        }

        return $message;
    }

    /**
     * Load languages messages from directory, files in format:
     *      en.json
     *      ru.json
     *      en.yml
     *      en.php ...etc
     *
     * @param string $directory
     * @param array $formats
     */
    public function loadDirectory(string $directory, array $formats = ['json', 'yml', 'php'])
    {
        $files = fs::scan($directory, ['excludeDirs' => true], 1);

        foreach ($files as $file) {
            if (arr::has($formats, fs::ext($file))) {
                $this->load(fs::nameNoExt($file), $file);
            }
        }
    }

    /**
     * @param string $lang
     * @param string $file
     */
    public function load(string $lang, string $file)
    {
        $event = new Event('load', $this, null, ['lang' => $lang, 'file' => $file]);
        $this->trigger($event);

        if (!$event->isConsumed()) {
            Logger::info("Load messages for '{0}' language from file {1}", $lang, $file);

            if (fs::ext($file) === 'php') {
                $messages = include $file;
            } else {
                $messages = fs::parse($file);
            }

            $this->addMessages($lang, $messages);
        }
    }

    /**
     * @param string $lang
     * @param array $messages
     */
    public function addMessages(string $lang, array $messages)
    {
        $event = new Event('addMessages', $this, null, ['lang' => $lang, 'messages' => $messages]);
        $this->trigger($event);

        if (!$event->isConsumed()) {
            if ($this->messages[$lang]) {
                foreach ($messages as $key => $message) {
                    $this->messages[$key] = $message;
                }
            } else {
                $this->messages[$lang] = $messages;
            }
        }
    }
}