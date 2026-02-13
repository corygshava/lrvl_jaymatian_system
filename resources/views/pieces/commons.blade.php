{{-- modals area --}}
    <button type="button" class="btn btn-primary btn-sm w3-hide" data-toggle="modal" data-target="#confirmerBox" data-role="toggleConfirmModal">Launch Dialog</button>

	<div class="modal fade" style="display: none;" id="confirmerBox" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content panelbg modal-md borderless themeround">
				<div class="modal-header">
					<span class="h4 modal-title">Confirm Action</span>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					do you want to delete this
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary except themeround" data-dismiss="modal" data-subrole="cancel">cancel</button>
					<button class="btn btn-primary except themeround" data-subrole="continue">continue &raquo;</button>
				</div>
			</div>
		</div>
	</div>

    <button type="button" class="btn btn-primary btn-sm w3-hide" data-toggle="modal" data-target="#dashmodal" data-role="toggleDashModal">Launch Dialog</button>

    <div class="modal fade" style="display: none;" id="dashmodal" tabindex="-1" role="dialog" aria-labelledby="dashmodaltitle" aria-hidden="true" data-role="dashmodal">
        <div class="modal-dialog" role="document">
            <div class="modal-content panelbg modal-md borderless themeround">
                <div class="modal-header">
                    <span class="h4 modal-title" id="dashmodaltitle">Info</span>
                    <button type="button themetxt" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    do you want to delete this
                </div>
                <div class="modal-footer">
                    <button type="button" class="mybtn secondary sm" data-dismiss="modal" data-subrole="close">close</button>
                    <button type="button" class="mybtn primary sm" data-subrole="continue">continue &raquo;</button>
                    <!-- {{-- <a href="#" data-subrole="continue">click this</a> --}} -->
                </div>
            </div>
        </div>
    </div>
{{-- end of modals area --}}

{{-- common ui forms --}}
    @auth
        <form action="./logout" method="post" id="logoutform" data-blockdefault="no">@csrf</form>
    @endauth
{{-- end of common ui forms --}}

{{-- session flashes --}}
    @if ($errors->any())
		<script>
			@foreach ($errors->all() as $err)
				alert_danger(`{{$err}}`,10);
			@endforeach
		</script>
	@endif

    @if (session()->has('message'))
		<script>alert_success(`{{session('message')}}`,15);</script>
	@endif
{{-- end of session flashes --}}

<script>
    const confirm_modal = document.querySelector('#confirmerBox');
    const confirm_toggler = document.querySelector('[data-role="toggleConfirmModal"]');
    const info_modal = document.querySelector('#dashmodal');
    const info_toggler = document.querySelector('[data-role="toggleDashModal"]');
</script>
