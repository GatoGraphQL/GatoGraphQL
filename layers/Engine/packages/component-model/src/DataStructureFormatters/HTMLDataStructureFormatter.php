<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructureFormatters;

use PoP\ComponentModel\DataStructureFormatters\AbstractDataStructureFormatter;

class HTMLDataStructureFormatter extends AbstractDataStructureFormatter
{
    public function getContentType(): string
    {
        return 'text/html';
    }

    public function getOutputContent(array &$data): string
    {
        return sprintf(
            '
            <html>
                <body>
                    %s
                    %s
                </body>
            </html>
            ',
            $this->getCSSStyles(),
            $this->arrayToHtmlTableRecursive($data)
        );
    }

    protected function getCSSStyles(): string
    {
        return <<<EOF
        <style>
        table {
            #width: 100%;
            #border: 1px solid #000;
            font-family: Arial, Helvetica, sans-serif;
            background-color: white;
        }
        th, td {
            #text-align: left;
            #vertical-align: top;
            border: 1px solid #000;
            border-collapse: collapse;
            padding: 0.3em;
            background-color: lavender;
        }
        th:nth-child(1), td:nth-child(1) {
            background-color: lightblue;
        }
        </style>
        EOF;
    }

    /**
     * @see https://stackoverflow.com/a/36760478
     */
    protected function arrayToHtmlTableRecursive($arr)
    {
        $str = "<table><tbody>";
        foreach ($arr as $key => $val) {
            $str .= "<tr>";
            $str .= "<td>$key</td>";
            $str .= "<td>";
            if (is_array($val)) {
                if (!empty($val)) {
                    $str .= $this->arrayToHtmlTableRecursive($val);
                }
            } else {
                $str .= "<strong>$val</strong>";
            }
            $str .= "</td></tr>";
        }
        $str .= "</tbody></table>";
        return $str;
    }

    public function getName(): string
    {
        return 'html';
    }
}
