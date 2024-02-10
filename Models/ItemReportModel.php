<?php

namespace Models;

use Api\Database;

class ItemReportModel
{
    private $db;
    public $id;
    public $status;
    public $missing;
    public $filter;
    public $value;
    public $sort;
    public $date_start;
    public $date_end;


    public function __construct($data = '')
    {
        $this->db = new \Api\Database();

        if ($data) {
            $this->id = $data['id'] ?? '';
            $this->status = $data['status'] ?? '';
            $this->missing = $data['missing'] ?? '';
            $this->filter = $data['filter'] ?? '';
            $this->value = $data['value'] ?? '';
            $this->sort = $data['sort'] ?? '';
            $this->date_start = $data['date_start'] ?? '';
            $this->date_end = $data['date_end'] ?? '';
        }
    }

    public function returnValues()
    {
        $sql = "SELECT SUM(i.".$this->value.") as value, COUNT(i.id) as count, ".$this->filter." as filter";
        $sql .= " FROM item i";
        if ($this->status != '') {
            $sql .= " WHERE status  = '".$this->status."' AND ".$this->filter." != ''" ;
        }
        $sql .= " GROUP BY  i.".$this->filter;
        $sql .= " ORDER BY ".$this->sort." DESC";
        //echo $sql;
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $items = array();
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            echo json_encode($items);
        } else {
            echo json_encode(array("message" => "No items found."));
        }

        $stmt->close();
    }
    public function doInsuranceReport()
    {
        $sql = "SELECT id, name, brand, model, serial_number, replacement_value from item where insured = 1";

        //echo $sql;
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $items = array();
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            return ($items);
        } else {
            echo json_encode(array("message" => "No items found."));
        }

        $stmt->close();
    }
    public function doTaxFormsReport()
    {
        $sql = "SELECT date, name, COALESCE(SUM(g.venue_payout),SUM(amount)) as amount, tax_forms FROM profit_loss pl";
        $sql .= " LEFT JOIN gig g on g.profit_loss_id = pl.id";
        $sql .= " WHERE tax_forms = '1'";
        if ($this->date_start && $this->date_end) {
            $sql .= " AND date >= '". $this->date_start."' AND date <= '". $this->date_end."'";
        }
        $sql .= "GROUP BY name";
        //  echo $sql;
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $items = array();
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            return ($items);
        } else {
            echo json_encode(array("message" => "No items found."));
        }

        $stmt->close();
    }

    public function doReturnGigDetails()
    {
        $sql ="select v.name, round(avg(g.venue_payout),2) as venue_average, round(avg(g.tips),2) as tips_average, COUNT(g.gig_id) as played from gig g LEFT JOIN venue v on v.venue_id = g.venue_id ";

        if ($this->date_start && $this->date_end) {
            $sql .= " LEFT JOIN profit_loss pl on pl.id = g.profit_loss_id WHERE date >= '". $this->date_start."' AND date <= '". $this->date_end."'";
        }
        $sql .= " group by v.name";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        //echo $sql;
        if ($result->num_rows > 0) {
            $items = array();
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            return ($items);
        } else {
            echo json_encode(array("message" => "No items found."));
        }

        $stmt->close();
    }
    public function doReturnProfitLoss()
    {
        $sql ="SELECT YEAR(date) AS year, MONTH(date) AS month, COALESCE(SUM(pl.amount), 0) AS total,";
        $sql .= " (SELECT COALESCE(SUM(amount),0) FROM profit_loss WHERE amount > 0 AND YEAR(date) = year AND MONTH(date) = month) AS income,";
        $sql .= " (SELECT COALESCE(SUM(amount),0) FROM profit_loss WHERE amount < 0 AND YEAR(date) = year AND MONTH(date) = month) AS expense ";

        $sql .= " FROM profit_loss pl ";
        if ($this->date_start && $this->date_end) {
            $sql .= " WHERE date >= '". $this->date_start."' AND date <= '". $this->date_end."'";
        }
        $sql .=  " GROUP BY year, month ";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        //	echo $sql;
        if ($result->num_rows > 0) {
            $items = array();
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            return ($items);
        } else {
            echo json_encode(array("message" => "No items found."));
        }

        $stmt->close();
    }
    public function doReturnProfitLossCategory()
    {
        $sql ="SELECT YEAR(date) AS year, category, SUM(pl.amount) AS total";
        $sql .= " FROM profit_loss pl ";
        $sql .= "WHERE amount < 0 ";
        if ($this->date_start && $this->date_end) {
            $sql .= " AND date >= '". $this->date_start."' AND date <= '". $this->date_end."'";
        }
        $sql .=  " GROUP BY year, category ";
        //	echo $sql;
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $items = array();
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            return ($items);
        } else {
            echo json_encode(array("message" => "No items found."));
        }

        $stmt->close();
    }
    public function doReturnOutstandingPayments()
    {
        $sql ="SELECT MONTH(date) AS month, category, SUM(pl.amount) AS total";
        $sql .= " FROM profit_loss pl ";
        $sql .= "WHERE paid = 0 ";
        if ($this->date_start && $this->date_end) {
            $sql .= " AND date >= '". $this->date_start."' AND date <= '". $this->date_end."'";
        }
        $sql .=  " GROUP BY month, category ";
        //	echo $sql;
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $items = array();
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
            return ($items);
        } else {
            echo json_encode(array("message" => "No items found."));
        }

        $stmt->close();
    }
}
