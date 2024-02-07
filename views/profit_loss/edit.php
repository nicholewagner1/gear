<form id="profitLossForm">
    <input type="hidden" name="id" value="<?= $id ?? ''; ?>" />

    <div class="form-group row">
        <div class="col">
            <label for="date">Date:</label>
            <input type="date" class="form-control" name="date" value="<?= $date ?? ''; ?>" required />
        </div>
        <div class="col">
            <label for="name">Name:</label>
            <input type="text" class="form-control" name="name" value="<?= $name ?? ''; ?>" required />
        </div>
    </div>

    <div class="form-group row">
        <div class="col">
            <label for="category">Category:</label>
            <select data-table="profit_loss" class="autocomplete category form-control" multiple id="category" name="category" value="<?= $category ?? ''; ?>" /></select>
            <input type="text" id="subcategory" class="form-control" name="subcategory" value="<?= $subcategory ?? ''; ?>" />

        </div>
        <div class="col">
            <label for="amount">Amount:</label>
            <input type="number" id="amount" class="form-control" name="amount" value="<?= $amount ?? ''; ?>" required />
        </div>
        <div class="col">

            <label for="paid">Paid:</label>
            <span class="" data-field="paid" data-itemid="<?= $id ?? ''; ?>">
                <i class="fa-solid fa-circle<?= $paidCheck ?? '';?>"></i> </span>
        </div>
        <div class="col">
            <label for="tax_forms">Tax Forms:</label>
            <span class="" data-field="tax_forms" data-itemid="<?= $id ?? ''; ?>">
                <i class="fa-solid fa-circle<?= $tax_formsCheck ?? '';?>"></i> </span>
        </div>
    </div>
    <div class="form-group">
        <label for="profit_loss_notes">Notes:</label>
        <textarea class="form-control" name="profit_loss_notes"><?= $notes ?? ''; ?></textarea>
    </div>



    <div class="form-group">
        <label for="account">Account:</label>
        <input type="text" id="amount" class="form-control" name="account" value="<?= $account ?? ''; ?>" />
    </div>

    <div id="gigInfo" class="hidden">
        <!-- Additional fields for insertGigInfo function here-->
        <div class="form-group">
            <input type="hidden" name="gig_id" value="<?= $gig_id ?? ''; ?>" />

            <label for="venue_id">Venue ID:</label>
            <select id="venue_id" data-table="venue" data-return="id" class="autocomplete venue_id form-control" multiple name="venue_id" value="<?= $venue_id ?? ''; ?>" /></select>
        </div>

        <div class="form-group row">
            <div class="col">
                <label for="venue_payout">Venue Payout:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                    </div><input type="number" class="gig_math form-control" id="venue_payout" name="venue_payout" value="<?= $venue_payout ?? ''; ?>" />
                </div>
            </div>
            <div class="col">

                <label for="merch">Merch:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                    </div>
                    <input type="number" class="gig_math form-control" id="merch" name="merch" value="<?= $merch ?? ''; ?>" />
                </div>
            </div>

            <div class="col">
                <label for="tips">Tips:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                    </div>
                    <input type="number" class="gig_math form-control" id="tips" name="tips" value="<?= $tips ?? ''; ?>" />
                </div>
            </div>

            <div class="col">
                <label for="cost_to_play">Cost to Play:</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupPrepend">$</span>
                    </div>
                    <input type="number" class="form-control" name="cost_to_play" value="<?= $cost_to_play ?? ''; ?>" />
                </div>
            </div>

            <div class="col">
                <label for="show_length">Show Length:</label>
                <div class="input-group">

                    <input type="number" class="form-control" name="show_length" value="<?= $show_length ?? ''; ?>" />
                    <div class="input-group-append">
                        <span class="input-group-text" id="inputGroupPrepend">min</span>
                    </div>
                </div>
            </div>
        </div>



        <div class="form-group">
            <label for="gig_notes">Gig Notes:</label>
            <textarea class="form-control" name="gig_notes"><?= $gig_notes ?? ''; ?></textarea>
        </div>
    </div>

    <button id="profitLossSubmit" class="btn btn-primary">Submit</button>
</form>