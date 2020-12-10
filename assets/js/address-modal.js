$(document).ready(function() {
    $('.add-address-action').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        const modal = getAddressModal();
        $('.main-container').after(modal);
        $('.addressRequest #addressModalLabel').html('Add new address');
        $('.addressRequest #addressSubmitBtn').attr('value', 'Add');
        $('.addressRequest #addressModal').modal();
    });
    $('.edit-address-action').on('click', function(event) {
        event.preventDefault();
        $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').hide();
        const modal = getAddressModal();
        $('.main-container').after(modal);
        $('.addressRequest #addressModalLabel').html('Edit address');
        $('.addressRequest #addressNum').val($(this).closest("tr").find("th.address-num").text());
        $('.addressRequest #streetName').val($(this).closest("tr").find("td.address-street-name").text());
        $('.addressRequest #houseNumber').val($(this).closest("tr").find("td.address-house-number").text());
        $('.addressRequest #zipCode').val($(this).closest("tr").find("td.address-zip-code").text());
        $('.addressRequest #city').val($(this).closest("tr").find("td.address-city").text());
        $('.addressRequest #addressSubmitBtn').attr('value', 'Submit changes');
        $('.addressRequest #addressModal').modal();
    });
    $('body').on('hide.bs.modal', '#addressModal', function () {
        setTimeout(function() {
            $('#addressModal').remove();
            $('.itQKmP, .hZAwTR, .iZQJIb, .muNJM').show()
        }, 400);
    });
});

function getAddressModal() {
    return `
        <div id="addressRequest" class="addressRequest">
            <form method="post" id="address-form" name="address-form" action="/account/my-addresses">
                <div aria-hidden="true" aria-labelledby="addressModalLabel" class="modal fade" id="addressModal" role="dialog" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered" style="max-width: 650px" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addressModalLabel"></h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <input class="form-control" id="addressNum" type="hidden" name="addressNum">
                                    <div class="col-sm-8">
                                        <label class="control-label" for="streetName">Street name<span style="color: red">*</span></label>
                                        <input class="form-control" id="streetName" type="text" name="streetName" placeholder="Enter street name" autofocus>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label" for="houseNumber">House number<span style="color: red">*</span></label>
                                        <input class="form-control" id="houseNumber" type="text" placeholder="Your house number" name="houseNumber">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="control-label" for="zipCode">Zip code<span style="color: red">*</span></label>
                                        <input class="form-control" id="zipCode" type="text" placeholder="Enter zip code" name="zipCode">
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="control-label" for="city">City<span style="color: red">*</span></label>
                                        <input class="form-control" id="city" type="text" placeholder="Enter city" name="city">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                                <input class="btn btn-primary" name="address-submit" id="addressSubmitBtn" value="" type="submit">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    `;
}
