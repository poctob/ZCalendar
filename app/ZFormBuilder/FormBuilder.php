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

namespace App\ZFormBuilder;

use Nette, 
        Nette\Utils\Strings;

/**
 * Description of FormBuilder
 *
 * @author Alex Pavlunenko <alexp at xpresstek.net>
 */
class FormBuilder {

    public function __construct() {
        
    }

    /**
     * Returns a list with the names of available form components.
     * Essentially it queries Nette\Forms\Container class for add* methods,
     * and then validates those to make sure that they are valid components.
     */
    public function getAvailableComponents() {
        $allmethods = get_class_methods('\Nette\Forms\Form');
        $addmethods = array();

        foreach ($allmethods as $method) {
            if (Strings::startsWith($method, 'add')) {
                $addmethods[] = Strings::substring($method, 3);
            }
        }

        return $addmethods;
    }

    /**
     * Builder form factory.
     * @return Nette\Application\UI\Form
     */
    public function createComponentBuilderForm() {
        $form = new Nette\Application\UI\Form;
        $components=$this->getAvailableComponents();
        $form->addMultiSelect("components_select",
                "Available Form Components",
                $components,
                count($components));
        return $form;        
    }

}
