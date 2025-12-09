<style>
a.close_corner_top_left {
	position: absolute;
	top: -14px;
	left: -10px;
}
.mr-20 {
	margin-right:20px;
}
.dataTables_filter, .dataTables_length {
	display: none;
}

.bg-purple {
    background-color: var(--purple);
    color: var(--white);
}

.bg-pink {
    background-color: var(--pink);
    color: var(--white);
}

.bg-orange {
    background-color: var(--orange);
    color: var(--white);
}

[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
	position: static!important;
	left: 0px!important;
	opacity: 1!important;
	visibility: visible!important;
	pointer-events: all!important;
}		 
</style>
<div class="row">
	<div class="col-12">
		<div class="card"> 
		<div class="card-header"></div>
			<div class="card-body">


					 
				<form class="form-horizontal searchTeacher" action="#" method="post" id="search-teacher">
           <div class="row"> 
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="s_teacher_name">Tanggal</span>
                        </div>
                        <input type="date" class="form-control" value="" aria-describedby="s_teacher_name" name="s_teacher_name"   />
                    </div>
                </div>
                <div class="col">
                    <button type="button" id="btn-search_teacher" class="btn btn-primary shadow">
                        <i class="fa fa-search fa-fw"></i>
                        Cari
                    </button>
                </div>
           </div>
				</form>
				<hr>
 
 

										
				<div class="table-responsive mt-1">
					<table id="tbl_teacher" class="table table-striped table-sm nowrap">
						<thead class="bg-dark text-white">
							<tr>  
								<th>#</th> 
								<th>User</th>   
								<th>IP Address</th>   
								<th>Tanggal</th>   
								<th>Keterangan</th>    
								<th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


 