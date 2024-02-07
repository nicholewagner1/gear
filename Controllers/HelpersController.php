<?php

namespace Controllers;

use Models\HelpersModel;

class HelpersController
{
    public function generateTableHeaders($id, $columns, $class = 'mt-3')
    {
        echo '<table id="'.$id.'" class="table "'.$class.'">';
        echo '<thead>';
        echo '<tr>';
        foreach ($columns as $column) {
            echo '<th>' . htmlspecialchars($column) . '</th>';
        }
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
    }
    public function generateTableFooters()
    {
        echo '</tbody>';
        echo '</table>';
        echo '<script src="/js/table_helper.js"></script>';
    }

    public function autocomplete($data)
    {
        $helperModel = new \Models\HelpersModel($data);
        $helperModel->returnAutocompleteData();
    }

    public function delete($data)
    {
        $helperModel = new \Models\HelpersModel($data);
        $helperModel->doDeleteData();
    }
    public function updateField($data)
    {
        $helperModel = new \Models\HelpersModel($data);
        $helperModel->doUpdateFieldData();
    }
}
