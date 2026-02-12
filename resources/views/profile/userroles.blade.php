<div class="spacy-md_">
    <div class="distance-md flowline spread overflow">
        <div>
            <span class="text-muted h4">User Role management</span>
        </div>
    </div>

    <section class="distance-sm">
        <div>
            <div class="data-container w3-transparent animate-fade-in">
                <!-- Data Header -->
                <div class="data-header">
                    <div class="d-flex align-items-center gap-md">
                        <h4 class="data-title spacy-sm"><b>All Students</b></h4>
                    </div>
                    <div class="data-actions">
                        <div class="search-box">
                            <input type="text" placeholder="Search clients, jobs and operations" id="quickSearch">
                            <button><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>

                <div class="data-body spacy-sm">
                    <div class="content-card" style="flex: 1 0 240px;max-width: 250px;">
                        <div class="spacy-sm">
                            <h3>Admin</h3>
                        </div>
                        <div class="flowline gap-tn">
                            <button class="btn btn-primary btn-sm">
                                <i class="fa fa-eye"></i>
                                permissions
                            </button>
                            <button class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i>
                                delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#editAddModal">
    Launch
</button>

<!-- Modal -->
<div class="modal fade" id="editAddModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">sm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                form goes here
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

