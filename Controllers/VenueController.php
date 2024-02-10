<?php

namespace Controllers;

use Models\VenueModel;

class VenueController
{
    public function returnVenues($data = '', $autocomplete = '0', $id='', $filter='', $value = '')
    {
        if ($data == '') {
            $data = array('id'=> $id);
        }
        $venueModel = new VenueModel($data);
        $venue = $venueModel->doReturnVenues();
        if ($autocomplete == 1) {
            echo json_encode($venue);
        } else {
            $helper = new \Controllers\HelpersController();
            $columnNames = ['Name', 'City', 'State', 'Venue Type', 'Status', 'Action'];
            $helper->generateTableHeaders('venue', $columnNames);
            foreach ($venue as $row) {
                $id = $row['venue_id'];
                $name = $row['name'];
                $type = $row['venue_type'] ?? 'edit';
                $booking_contact = $row['booking_contact'] ?? 'edit';
                $city = $row['city'] ?? 'edit';
                $state = $row['state'] ?? 'edit';
                $status= $row['status'] ?? 'edit';
                $roundtrip_mileage = $row['roundtrip_mileage'] ?? '0';

                include($_SERVER['DOCUMENT_ROOT'].'/views/venue/table_row.php');
            }
            $helper->generateTableFooters();
        }
    }

    public function editVenue($id = '')
    {
        if ($id != '') {
            $data = array('id'=>$id);

            $venueModel = new VenueModel($data);
            $venue = $venueModel->doReturnVenues();

            foreach ($venue as $row) {
                $id = $row['venue_id'];
                $name = $row['name'];
                $venue_type = $row['venue_type'] ?? '';
                $type = $row['type'] ?? '';
                $booking_contact = $row['booking_contact'] ?? '';
                $city = $row['city'] ?? '';
                $state = $row['state'] ?? '';
                $country = $row['country'] ?? '';
                $roundtrip_mileage = $row['roundtrip_mileage'] ?? '';

                include($_SERVER['DOCUMENT_ROOT'].'/views/venue/edit.php');
            }
        } else {
            include($_SERVER['DOCUMENT_ROOT'].'/views/venue/edit.php');
        }
    }
    public function gigsAtVenue($id = '')
    {
        $data = array('id'=>$id);
        $helper = new \Controllers\HelpersController();
        $columnNames = ['Date', 'Name', 'Notes','Edit'];
        $helper->generateTableHeaders('gigsVenue', $columnNames);
        $venueModel = new VenueModel($data);
        $venue = $venueModel->doReturnGigsAtVenue();

        foreach ($venue as $row) {
            $id = $row['id'];
            $name = $row['name'];
            $date = $row['date'];
            $gig_notes = $row['gig_notes'] ?? '--';

            include($_SERVER['DOCUMENT_ROOT'].'/views/venue/gigs_at_venue.php');
        }
        $helper->generateTableFooters();
    }


    public function upsertVenueData($data)
    {
        $venueModel = new \Models\VenueModel($data);
        $venueModel->doUpsertVenueData();
    }
}
