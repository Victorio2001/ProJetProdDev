<?php

namespace BibliOlen\Core;

class Table
{
    private array $data;
    private array $headers;

    public function __construct($data, $headers = array())
    {
        $this->data = $data;
        $this->headers = $headers;
    }

    public static function createButton(string $fnName, string $text, string $extraClass = '', string $dataContext = ''): string
    {
        return "<button class='px-4 py-2 bg-primary-900 text-white rounded-2xl hover:bg-primary-700 mt-4 lg:mt-0 $extraClass' " .
            "onclick=\"$fnName(this)\" data-context=\"$dataContext\">$text</button>";
    }

    public static function createBadge(string $bgColor, string $text, string $extraClass = '', string $textColor = "#000000"): string
    {
        return "<span class='inline-block px-3 py-1 text-xs uppercase rounded-full $extraClass' style='background-color: $bgColor; color: $textColor;'>$text</span>";
    }

    public static function createActions(array $actions): string
    {
        return implode(' ', $actions);
    }

    public function build(): string
    {
        $table = '<table class="border-collapse w-full max-w-6xl shadow-md rounded my-6 mx-auto mx-4 lg:mx-auto">';
        $table .= '<thead>';
        $table .= '<tr>';

        foreach ($this->headers as $header) {
            $table .= '<th class="p-3 font-bold uppercase hover:cursor-pointer bg-gray-700 text-white border border-gray-300 hidden lg:table-cell">';
            $table .= '<div class="flex justify-between items-center">';
            $table .= '<div class="flex justify-between items-center">';
            $table .= "<span>$header</span>";
            $table .= '<span>';
            $table .= '<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">';
            $table .= '<path d="M18.2 9.3l-6.2-6.3-6.2 6.3c-.2.2-.3.4-.3.7s.1.5.3.7c.2.2.4.3.7.3h11c.3 0 .5-.1.7-.3.2-.2.3-.5.3-.7s-.1-.5-.3-.7zM5.8 14.7l6.2 6.3 6.2-6.3c.2-.2.3-.5.3-.7s-.1-.5-.3-.7c-.2-.2-.4-.3-.7-.3h-11c-.3 0-.5.1-.7.3-.2.2-.3.5-.3.7s.1.5.3.7z"></path>';
            $table .= '</svg>';
            $table .= '</span>';
            $table .= '</div>';
            $table .= '</div>';
            $table .= '</th>';
        }

        $table .= '</tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        foreach ($this->data as $i => $dataRow) {
            $table .= '<tr class="book-data bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0">';
            foreach ($dataRow as $j => $dataCell) {
                $table .= '<td class="w-full lg:w-auto px-3 pb-3 pt-8 lg:p-3 text-gray-800 text-left border border-b block lg:table-cell relative lg:static mx-4 lg:mx-0">';
                $table .= "<span class='lg:hidden px-3 pb-3 absolute top-0 left-0 bg-blue-200 px-2 py-1 text-xs font-bold uppercase'>";
                $table .= $j;
                $table .= '</span>';
                $table .= $dataCell;
                $table .= '</td>';
            }
            $table .= '</tr>';
        }
        $table .= '</tbody>';
        $table .= '</table>';
        return $table;
    }
}

