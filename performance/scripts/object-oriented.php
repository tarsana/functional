<?php
namespace Performance;

Class File {
    protected $path;
    public function __construct($path) {
        $this->path = $path;
    }
    public function read() {
        return file_get_contents($this->path);
    }
    public function write($content) {
        file_put_contents($this->path, $content);
    }
}

class Formatter {
    public function format($words) {
        $text = '';
        foreach ($words as $occ => $list) {
            $text .= "{$occ}: {$this->formatList($list)}\n";
        }
        return $text;
    }

    protected function formatList($list) {
        $values = array_map(function(Word $w) {
            return $w->value();
        }, $list);
        return implode(', ', $values);
    }
}

class Word {
    protected $value;
    protected $occurrences;

    public function __construct($value, $occurrences) {
        $this->value = $value;
        $this->occurrences = $occurrences;
    }

    public function value() {
        return $this->value;
    }

    public function occurrences() {
        return $this->occurrences;
    }
}

class Parser {
    protected $file;
    protected $tokens;

    public function __construct(File $file) {
        $this->file = $file;
        $this->tokens = [];
    }

    public function words() {
        $this->fillTokens();
        $words = [];
        foreach (array_count_values($this->tokens) as $value => $occ) {
            $words[] = new Word($value, $occ);
        }
        return $words;
    }

    protected function fillTokens() {
        $text = $this->file->read();
        $text = trim(preg_replace('/[^a-zA-Z0-9]+/', ' ', $text));
        $this->tokens = explode(' ', $text);
    }

}

class Collection {
    protected $list;

    public function __construct($list) {
        $this->list = $list;
    }

    public function groupByOccurrences() {
        $occurrencesValues = $this->getOccurrencesValues();
        arsort($occurrencesValues);
        $result = [];
        foreach ($occurrencesValues as $occ) {
            $result[$occ] = $this->getWordsHavingOccurrences($occ);
        }
        return $result;
    }

    protected function getOccurrencesValues() {
        $result = [];
        foreach ($this->list as $word) {
            $result[$word->occurrences()] = true;
        }
        return array_keys($result);
    }

    protected function getWordsHavingOccurrences($number) {
        $result = [];
        foreach ($this->list as $word) {
            if ($word->occurrences() == $number)
                $result[] = $word;
        }
        usort($result, function(Word $w1, Word $w2) {
            return strcmp($w1->value(), $w2->value());
        });
        return $result;
    }

}

class Program {
    public static function run() {
        $input = new File('php://stdin');
        $output = new File('php://stdout');

        $parser = new Parser($input);
        $words = $parser->words();

        $collection = new Collection($words);

        $formatter = new Formatter;
        $output->write($formatter->format($collection->groupByOccurrences()));
    }
}

Program::run();
