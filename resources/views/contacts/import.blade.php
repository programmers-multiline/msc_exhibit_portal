@extends('layouts.app')

@section('content')
    
<style>
        /* Itago ang loading at success indicator sa simula */
        #loadingState, #successState { display: none; }
</style>
<div class="mobile-menu-overlay"></div>
	<!-- <div class="main-container">
		<div class="pd-ltr-10 xs-pd-10-10">
			<div class="min-height-200px"> -->
				<div class="container pd-0">
					<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>Contact Directory</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item"><a href="index.html">Home</a></li>
										<li class="breadcrumb-item active" aria-current="page">Contact Directory</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
<div class="container mt-5" style="max-width: 600px;">
				<div class="card shadow">
					<div class="card-header  text-white" style="background-color:#031e23; color:whitesmoke;">
						<!-- <h3 class="mb-0"> -->
							Upload at I-parse ang CSV
						<!-- </h3> -->
					</div>
					<div class="card-body">
						
						<!-- 1. FORM STATE (Default) -->
						<form id="uploadForm" enctype="multipart/form-data">
							@csrf
							<div class="mb-3">
								<label for="file" class="form-label">Pumili ng CSV File</label>
								<input type="file" name="file" id="file" class="form-control" required>
								<div id="errorArea" class="text-danger mt-2 small"></div>
							</div>
							<button type="submit" id="submitBtn" class="btn btn-success w-100">Simulan ang Pag-import</button>
						</form>

						<!-- 2. LOADING STATE (Habang nag-uupload at nagpa-parse) -->
						<div id="loadingState" class="text-center py-4">
							<div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
							<h5 class="text-secondary fw-bold">Uploading and Parsing...</h5>
							<p class="text-muted small mb-0">Huwag isara o i-refresh ang page habang pinoproseso ang data.</p>
						</div>

						<!-- 3. SUCCESS STATE (Kapag tapos na) -->
						<div id="successState" class="py-4">
							<div class="text-center mb-3">
								<div class="text-success" style="font-size: 3rem;">✔️</div>
								<h5 class="text-success fw-bold">Finish Uploading!</h5>
							</div>
							
							<!-- Dito ipapakita ang breakdown ng mga bilang -->
							<div class="bg-white border rounded p-3 my-3 shadow-sm mx-auto" style="max-width: 400px;">
								<div class="d-flex justify-content-between border-bottom py-2">
									<span class="text-muted fw-semibold">Total Processed:</span>
									<strong id="totalUploaded" class="text-dark">0</strong>
								</div>
								<div class="d-flex justify-content-between border-bottom py-2">
									<span class="text-success fw-semibold">🟢 Total New (Inserted):</span>
									<strong id="totalNew" class="text-success">0</strong>
								</div>
								<div class="d-flex justify-content-between py-2">
									<span class="text-warning fw-semibold">🟡 Total Duplicate (Skipped):</span>
									<strong id="totalDuplicate" class="text-warning">0</strong>
								</div>
							</div>

							<p id="successMessage" class="text-muted small text-center mb-4">Matagumpay na na-insert ang mga data sa kani-kanilang table.</p>
							<div class="text-center">
								<button onclick="window.location.reload()" class="btn btn-outline-primary btn-sm">Mag-upload ng Bago</button>
							</div>
						</div>
						<!-- Ending ng Success State -->

					</div>
				</div>
    </div>
			<!-- </div> -->
			
	


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

 <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Pigilan ang default page reload

            const form = this;
            const formData = new FormData(form);
            const errorArea = document.getElementById('errorArea');
            
            // Mga visual elements
            const formState    = document.getElementById('uploadForm');
            const loadingState = document.getElementById('loadingState');
            const successState = document.getElementById('successState');

            // Linisin ang lumang error message
            errorArea.innerText = '';

            // Ipakita ang "Uploading and Parsing..." indicator
            formState.style.display = 'none';
            loadingState.style.display = 'block';

            // Ipadala ang file sa Laravel gamit ang Fetch API
            fetch("{{ route('import.process') }}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Sabihin sa Laravel na ito ay AJAX request
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Kung may error sa validation (e.g. maling file type)
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
           .then(data => {
					// Itago ang loading at ipakita ang resulta
					loadingState.style.display = 'none';
					successState.style.display = 'block';
					
					// I-inject ang mga bilang mula sa server response papunta sa UI
					if(data.total_uploaded !== undefined) {
						document.getElementById('totalUploaded').innerText = data.total_uploaded.toLocaleString();
						document.getElementById('totalNew').innerText = data.total_new.toLocaleString();
						document.getElementById('totalDuplicate').innerText = data.total_duplicate.toLocaleString();
					}
					
					if(data.message) {
						document.getElementById('successMessage').innerText = data.message;
					}
				})
            .catch(error => {
                // Ibalik sa form kapag nagka-error para maayos ng user
                loadingState.style.display = 'none';
                formState.style.display = 'block';
                
                if (error.errors && error.errors.file) {
                    errorArea.innerText = error.errors.file[0];
                } else {
                    errorArea.innerText = 'May naganap na error. Subukang muli.';
                }
            });
        });
    </script>

@endsection