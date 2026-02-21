@props([
	'msg' => 'an error occured',
	'author' => ''
])

<?php
    $msg = isset($msg) ? $msg : "an error occured";
?>

<div class="spacy-lg w3-center">
    <div class="flow center">
        <span class="h3">Error</span>
        <p><?=$msg?></p>
		@if ($author !== '')
			<p><i><?=$author?></i></p>
		@endif
        <button type="button" class="mybtn primary" data-toggle="modal" data-target="#mymodal">reload page</button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="mymodal" tabindex="-1" role="dialog" aria-labelledby="Mytitle" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">sm</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
			</div>
			<div class="modal-body">
				something real
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>
