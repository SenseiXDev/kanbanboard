@if (Auth::user()->type == 'owner')
<form id='form_pad' method="post" enctype="multipart/form-data">
    @method('POST')
    <div class="modal-body" id="">
        <div class="row">

            <input type="hidden" name="contract_id" value="{{ $contract->id }}">


            <div class="form-control signature">
                <canvas id="signature-pad" class="signature-pad" height=200></canvas>
                <input type="hidden" name="owner_signature" id="SignupImage1">
            </div>
            <div class="mt-2">
                <button type="button" class="btn btn-sm btn-danger rounded-pill" id="clearSig">{{ __('Clear') }}</button>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-sm btn-secondary rounded-pill" data-dismiss="modal">
        <input type="button" id="addSig" value="{{ __('Sign') }}" class="btn btn-sm btn-primary rounded-pill">
    </div>
</form>
@else()
<form id='form_pad' method="post" enctype="multipart/form-data">
    @method('POST')
    @csrf
    <div class="modal-body" id="">
        <div class="row">

            <input type="hidden" name="contract_id" value="{{ $contract->id }}">
            <div class="form-control signature">
                <canvas id="signature-pad" class="signature-pad" height=200></canvas>
                <input type="hidden" name="client_signature" id="SignupImage1">
            </div>
            <div class="mt-2">
                <button type="button" class="btn btn-sm btn-danger rounded-pill" id="clearSig">{{ __('Clear') }}</button>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn btn-sm btn-secondary rounded-pill" data-dismiss="modal">
        <input type="button" id="addSig" value="{{ __('Sign') }}" class="btn btn-sm btn-primary rounded-pill">
    </div>
</form>
@endif

<script src="{{ asset('assets/libs/signature_pad.min.js') }}"></script>
<script>
    var signature = {
        canvas: null,
        clearButton: null,

        init: function init() {

            this.canvas = document.querySelector(".signature-pad");
            this.clearButton = document.getElementById('clearSig');
            this.saveButton = document.getElementById('addSig');
            signaturePad = new SignaturePad(this.canvas);


            this.clearButton.addEventListener('click', function(event) {

                signaturePad.clear();
            });

            this.saveButton.addEventListener('click', function(event) {
                var data = signaturePad.toDataURL('image/png');
                $('#SignupImage1').val(data);



                $.ajax({
                    url: '{{ route('signaturestore') }}',
                    type: 'POST',
                    data: $("form").serialize(),
                    success: function(data) {
                        console.log(data.status);
                        show_toastr('Success', data.msg, 'success');
                        setTimeout(() => {
                                location.reload();
                            }, 1000);

                        $('#commonModal').modal('hide');
                    },
                    error: function(data) {

                    }
                });


            });

        }
    };

    signature.init();
</script>
