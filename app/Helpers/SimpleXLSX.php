<?php

namespace App\Helpers;

class SimpleXLSX
{
    private $sheets;

    public static function parse($filename)
    {
        $xlsx = new self();
        if (!$xlsx->unzip($filename)) return false;
        if (!$xlsx->parseSheet()) return false;
        return $xlsx;
    }

    public function rows($sheetIndex = 0)
    {
        return $this->sheets[$sheetIndex]['data'];
    }

    private function unzip($filename)
    {
        $zip = new \ZipArchive();
        if ($zip->open($filename) === true) {
            $this->data = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                $this->data[$name] = $zip->getFromIndex($i);
            }
            $zip->close();
            return true;
        }
        return false;
    }

    private function parseSheet()
    {
        if (!isset($this->data['xl/sharedStrings.xml']) || !isset($this->data['xl/worksheets/sheet1.xml'])) return false;

        $sharedStrings = [];
        $xml = simplexml_load_string($this->data['xl/sharedStrings.xml']);
        foreach ($xml->si as $si) {
            $sharedStrings[] = (string) $si->t;
        }

        $rows = [];
        $sheet = simplexml_load_string($this->data['xl/worksheets/sheet1.xml']);
        foreach ($sheet->sheetData->row as $row) {
            $r = [];
            foreach ($row->c as $c) {
                $v = (string) $c->v;
                if ((string)$c['t'] === 's') {
                    $v = $sharedStrings[(int)$v];
                }
                $r[] = $v;
            }
            $rows[] = $r;
        }

        $this->sheets[0]['data'] = $rows;
        return true;
    }
}
