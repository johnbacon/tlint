<?php

namespace Tighten\TLint\Formatters;

use PhpParser\Lexer;
use PhpParser\Parser;
use Tighten\TLint\BaseFormatter;
use Tighten\TLint\Linters\Concerns\LintsBladeTemplates;
use Tighten\TLint\Linters\SpaceAfterBladeDirectives as Linter;

class SpaceAfterBladeDirectives extends BaseFormatter
{
    use LintsBladeTemplates;

    public const DESCRIPTION = Linter::DESCRIPTION;

    public function format(Parser $parser, Lexer $lexer): string
    {
        foreach ($this->getCodeLines() as $codeLine) {
            $matches = [];

            preg_match_all(
                Linter::DIRECTIVE_SEARCH,
                $codeLine,
                $matches,
                PREG_SET_ORDER
            );

            foreach($matches as $match) {
                if (in_array($match[1] ?? null, Linter::SPACE_AFTER) && ($match[2] ?? null) === '') {

                    $updatedCodeLine = str_replace($match[0], "@{$match[1]} {$match[3]}", $codeLine);

                    $this->code = str_replace($codeLine, $updatedCodeLine, $this->code);

                    $codeLine = $updatedCodeLine;
                }
            }
        }

        return $this->code;
    }
}
