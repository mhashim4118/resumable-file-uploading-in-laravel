<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>File Upload</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uppy@3.19.1/dist/uppy.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container pt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-header text-center">
                        <h5>Upload File</h5>
                    </div>

                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <input type="file" id="fileInput" style="display: none">
                        <button id="browseFile" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">Browse File</button>
                    </div>

                    <div class="progress" style="display: none">
                        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <div class="card-footer p-4" style="display: none">
                        <video id="videoPreview" src="" controls style="width: 100%; height: auto"></video>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style scoped>
        .progress {
            display: flex !important;
        }

    </style>
    <script src="https://cdn.jsdelivr.net/npm/tus-js-client@3.1.1/dist/tus.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uppy@3.19.1/dist/uppy.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var progress = document.querySelector('.progress');
            var progressBar = progress.querySelector('.progress-bar');
            document.getElementById('fileInput').addEventListener('change', function() {
                var fileInput = this;
                var file = fileInput.files[0];

                var upload = new tus.Upload(file, {
                    endpoint: '/tus'
                    , retryDelays: [0, 3000, 5000, 10000, 20000]
                    , metadata: {
                        filename: file.name
                        , filetype: file.type
                    , }
                    , onError: function(error) {
                        console.log('Failed because: ' + error)
                    }
                    , onProgress: function(bytesUploaded, bytesTotal) {
                        var percentage = ((bytesUploaded / bytesTotal) * 100).toFixed(2)
                        // Show the progress bar if not already visible
                        showProgress();

                        // Update the progress bar with the calculated percentage
                        updateProgress(progressBar, percentage);
                        console.log(bytesUploaded, bytesTotal, percentage + '%')
                    }
                    , onSuccess: function() {
                        console.log('Download %s from %s', upload.file.name, upload.url)
                    }
                , });

                upload.findPreviousUploads().then(function(previousUploads) {
                    // Found previous uploads so we select the first one.
                    if (previousUploads.length) {
                        upload.resumeFromPreviousUpload(previousUploads[0])
                    }

                    // Start the upload
                    upload.start()
                })


            });

            function showProgress() {
                console.log('inside showProgress')
                var progress = document.querySelector('.progress');
                progress.querySelector('.progress-bar').style.width = '0%';
                progress.querySelector('.progress-bar').innerHTML = '0%';
                progress.querySelector('.progress-bar').classList.remove('bg-success');
                progress.style.display = 'block';
            }

            function updateProgress(progressBar, value) {
                console.log('progressBar', progressBar);
                console.log('value', value);
                progressBar.style.width = `${value}%`;
                progressBar.innerHTML = `${value}%`;
                console.log('progress bar style width', progressBar.style.width);
                console.log('progress bar innerHTML', progressBar.innerHTML);
                if (value >= 0 && value < 25) {
                    console.log('inside 0 to 25');
                    progressBar.classList.remove('bg-warning', 'bg-info', 'bg-success');
                    progressBar.classList.add('bg-danger');
                } else if (value >= 25 && value < 50) {
                    console.log('inside 26 to 50');
                    progressBar.classList.remove('bg-info', 'bg-danger', 'bg-success');
                    progressBar.classList.add('bg-warning');
                } else if (value >= 50 && value < 75) {
                    console.log('inside 50 to 75');
                    progressBar.classList.remove('bg-danger', 'bg-warning', 'bg-success');
                    progressBar.classList.add('bg-info');
                } else if (value >= 75 && value <= 100) {
                    console.log('inside 76 to 100');
                    progressBar.classList.remove('bg-info', 'bg-warning', 'bg-danger');
                    progressBar.classList.add('bg-success');
                }
            }

            function hideProgress() {
                var progress = document.querySelector('.progress');
                progress.style.display = 'none';
            }

        });

    </script>
</body>
</html>
