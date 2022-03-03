<?php
/**
 * Created by PhpStorm.
 * User: szymon
 * Date: 3/14/19
 * Time: 8:33 AM
 */

namespace LukeSnowden\GoogleShoppingFeed\Formats;

use League\Csv\Writer;
use LukeSnowden\GoogleShoppingFeed;
use LukeSnowden\GoogleShoppingFeed\FeedInterface;
use LukeSnowden\GoogleShoppingFeed\Item;
use LukeSnowden\GoogleShoppingFeed\FeedAbstract;

class Csv extends FeedAbstract
{


    public function __construct()
    {
        $this->feed = Writer::createFromFileObject(new \SplTempFileObject());
    }

    public function createItem(){
        $item = new Item($this);
        $index = 'index_' . md5(microtime());
        $this->items[$index] = $item;
        $item->setIndex($index);
        return $item;
    }

    public function asRss($output = false)
    {
        if (ob_get_contents()) ob_end_clean();
        $this->addItemsToFeed();
        $data = $this->feed->getContent();
        if ($output) {
            header('Content-Type: application/xml; charset=utf-8');
            die($data);
        }
        return $data;
    }

    /**
     * Adds items to feed
     */
    private function addItemsToFeed()
    {
        $this->insertHeaderRow();
        $data = array();
        foreach ($this->items as $item) {
            $row = array();
            /** @var GoogleShoppingFeed\Node $itemNode */
            foreach ($item->nodes() as $itemNode) {
                $row[] = $itemNode->get('value');
            }
            $data[] = $row;
        }

        $this->feed->insertAll($data);
    }

    private function insertHeaderRow()
    {
        if (count($this->items) > 0) {
            $item = current($this->items);
            $headerRow = array();
            foreach ($item->nodes() as $key=>$node) {
                $headerRow[] = $key;
            }
        }

        $this->feed->insertOne($headerRow);
    }

}