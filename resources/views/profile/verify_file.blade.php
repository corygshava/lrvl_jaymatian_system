<div class="spacy-lg w3-center">
    <div class="flow center">
        <span class="h3">Redirecting</span>
        <p id="informer">navigating to file verification page shortly</p>
        <button class="mybtn primary" onclick="redirect_to_page()">Open again</button>
    </div>
</div>

<script>
    window['runOnAwake'] = () => {
        setTimeout(() => {
            redirect_to_page();
        },600);
    }

    window['redirect_to_page'] = () => {
        openinnewtab('./verify_file?fromdash=yes');
        informer.innerHTML = 'file verification page has been opened in a new tab';
    }
</script>
