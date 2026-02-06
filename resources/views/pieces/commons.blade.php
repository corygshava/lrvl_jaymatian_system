{{-- modals area --}}
    <button type="button" class="btn btn-primary btn-sm w3-hide" data-toggle="modal" data-target="#confirmerBox" data-role="toggleConfirmModal">Launch Dialog</button>

	<div class="modal fade" style="display: none;" id="confirmerBox" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content modebg">
				<div class="modal-header">
					<h5 class="modal-title">Confirm Action</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					do you want to delete this
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary except" data-dismiss="modal" data-subrole="cancel">cancel</button>
					<button class="btn btn-primary except" data-subrole="continue">continue &raquo;</button>
				</div>
			</div>
		</div>
	</div>
{{-- end of modals area --}}

{{-- common ui forms --}}
    @auth
        <form action="./logout" method="post" id="logoutform" data-onsubmit="checkready" data-blockdefault="no">@csrf</form>
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
