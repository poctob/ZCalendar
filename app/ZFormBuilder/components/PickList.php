<?php

/*
 * Copyright (C) 2014 Alex Pavlunenko <alexp at xpresstek.net>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\ZFormBuilder\Components;

use Nette\Application\UI;

/**
 * Description of PickList, drag and drop pick list component.
 *
 * @author Alex Pavlunenko <alexp at xpresstek.net>
 */
class PickList extends UI\Control {

    /**
     * Items located in the pick list
     * @var Staring array 
     */
    private $items;
    
    /**
     * Pick list title.
     * @var String 
     */
    private $title="Available items";
    
    /**
     * Title of the selected items cart.
     * @var String 
     */
    private $cart_title="Selected items";
    
    /**
     * Default constructor, initializes member variables.
     * @param String array $items
     */
    public function __construct($items, $title, $cart_title) {
        parent::__construct();
        $this->items = $items;
        $this->title = $title;
        $this->cart_title = $cart_title;
    }

    /**
     * Renders component.
     */
    public function render() {
        $template = $this->template;
        $template->setFile(realpath(__DIR__ .'/..') .'/templates/PickList.latte');
        $template->items = $this->items;
        $template->title = $this->title;
        $template->cart_title = $this->cart_title;
        $template->render();
    }

}
