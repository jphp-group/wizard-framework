<?php
namespace bundle\aceeditor;

use framework\web\UIModule;

/**
 * Class AceEditorModule
 * @package bundle\aceeditor
 */
class AceEditorModule extends UIModule
{
    /**
     * AceEditorModule constructor
     **/
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getResources(): array
    {
        $libs = [];

        $modes = [
            'abap', 'abc', 'actionscript', 'ada', 'apache_conf', 'applescript', 'asciidoc', 'assembly_x86', 'autohotkey',
            'batchfile', 'bro', 'c9search', 'c_cpp', 'cirru', 'clojure', 'cobol', 'coffee', 'coldfusion', 'csharp',
            'css', 'curly', 'd', 'dart', 'diff', 'django', 'dockerfile', 'dot', 'drools', 'eiffel', 'ejs', 'elixir',
            'elm', 'erlang', 'forth', 'fortran', 'ftl', 'gcode', 'gherkin', 'gitignore', 'glsl', 'gobstones',
            'golang', 'graphqlschema', 'groovy', 'haml', 'handlebars', 'haskell', 'haskell_cabal', 'haxe', 'hjson', 'html',
            'html_elixir', 'html_ruby', 'ini', 'io', 'jack', 'jade', 'java', 'javascript', 'json', 'jsoniq', 'jsp', 'jssm',
            'jsx', 'julia', 'kotlin', 'latex', 'lean', 'less', 'liquid', 'lisp', 'live_script', 'livescript', 'logiql',
            'lsl', 'lua', 'luapage', 'lucene', 'makefile', 'markdown', 'mask', 'matlab', 'maze', 'mel', 'mips_assembler',
            'mushcode', 'mysql', 'nix', 'nsis', 'objectivec', 'ocaml', 'pascal', 'perl', 'pgsql', 'php', 'pig', 'plain_text',
            'powershell', 'praat', 'prolog', 'properties', 'protobuf', 'python', 'r', 'razor', 'rdoc', 'red', 'rhtml',
            'ruby', 'rust', 'sass', 'scad', 'scala', 'scheme', 'scss', 'sh', 'sjs', 'smarty', 'snippets', 'soy_template',
            'space', 'sparql', 'sql', 'sqlserver', 'stylus', 'svg', 'swift', 'swig', 'tcl', 'tex', /*'text',*/ 'textile',
            'toml', 'tsx', 'turtle', 'twig', 'typescript', 'vala', 'vbscript', 'velocity', 'verilog', 'vhdl', 'wollok',
            'xml', 'xquery', 'yaml'
        ];

        $themes = [
            'ambiance', 'chaos', 'chrome', 'clouds', 'clouds_midnight', 'cobalt', 'crimson_editor', 'dawn', 'dracula',
            'dreamweaver', 'eclipse', 'github', 'gob', 'gruvbox', 'idle_fingers', 'iplastic', 'katzenmilch', 'kr_theme',
            'kuroir', 'merbivore', 'merbivore_soft', 'mono_industrial', 'monokai', 'pastel_on_dark', 'solarized_dark',
            'solarized_light', 'sqlserver', 'terminal', 'textmate', 'tomorrow', 'tomorrow_night', 'tomorrow_night_blue',
            'tomorrow_night_bright', 'tomorrow_night_eighties', 'twilight', 'vibrant_ink', 'xcode'
        ];

        foreach ($modes as $mode) {
            $libs[] = "res://js/aceeditor/mode-$mode.js";
        }

        foreach ($themes as $theme) {
            $libs[] = "res://js/aceeditor/theme-$theme.js";
        }

        return $libs;
    }

    /**
     * @return array
     */
    public function getRequiredResources(): array
    {
        return [
            'res://js/aceeditor/ace.js',
            'res://js/AceEditor.js'
        ];
    }
}