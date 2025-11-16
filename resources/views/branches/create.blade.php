
<!-- Modal Structure -->
<div class="modal fade" id="createBranchModal" tabindex="-1" aria-labelledby="createBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBranchModalLabel">Create New Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createBranchForm">
                    <div class="mb-3">
                        <label for="branchName" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" id="branchName" name="branchName" required>
                    </div>
                    <div class="mb-3">
                        <label for="branchEmail" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="branchEmail" name="branchEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="branchPhone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="branchPhone" name="branchPhone" required>
                    </div>
                    <button type="submit" class="btn bg-gradient-dark">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$('#createBranchForm').submit(function(e) {
    e.preventDefault();  // Prevent the default form submission

    var formData = $(this).serialize(); // Serialize the form data

    $.ajax({
        url: '/branches',  // Adjust the URL for your branches creation route
        method: 'POST',
        data: formData,
        success: function(response) {
            alert('Branch added successfully!');
            $('#createBranchModal').modal('hide'); // Hide the modal after success
            // Optionally, update the branch list dynamically
            $('#branchesList').append(`
                <li class="list-group-item">${response.name}</li>
            `);
        },
        error: function(error) {
            alert('An error occurred while adding the branch.');
        }
    });
});



</script>
<script src="{{ asset('js/plugins/choices.min.js') }}"></script>
        <script>
            if (document.getElementById('choices-gender')) {
                var gender = document.getElementById('choices-gender');
                const example = new Choices(gender);
            }

            if (document.getElementById('choices-language')) {
                var language = document.getElementById('choices-language');
                const example = new Choices(language);
            }

            if (document.getElementById('choices-skills')) {
                var skills = document.getElementById('choices-skills');
                const example = new Choices(skills, {
                    delimiter: ',',
                    editItems: true,
                    maxItemCount: 5,
                    removeItemButton: true,
                    addItems: true
                });
            }

            if (document.getElementById('choices-year')) {
                var year = document.getElementById('choices-year');
                setTimeout(function() {
                    const example = new Choices(year);
                }, 1);

                for (y = 1900; y <= 2020; y++) {
                    var optn = document.createElement("OPTION");
                    optn.text = y;
                    optn.value = y;

                    if (y == 2020) {
                        optn.selected = true;
                    }

                    year.options.add(optn);
                }
            }

            if (document.getElementById('choices-day')) {
                var day = document.getElementById('choices-day');
                setTimeout(function() {
                    const example = new Choices(day);
                }, 1);


                for (y = 1; y <= 31; y++) {
                    var optn = document.createElement("OPTION");
                    optn.text = y;
                    optn.value = y;

                    if (y == 1) {
                        optn.selected = true;
                    }

                    day.options.add(optn);
                }

            }

            if (document.getElementById('choices-month')) {
                var month = document.getElementById('choices-month');
                setTimeout(function() {
                    const example = new Choices(month);
                }, 1);

                var d = new Date();
                var monthArray = new Array();
                monthArray[0] = "January";
                monthArray[1] = "February";
                monthArray[2] = "March";
                monthArray[3] = "April";
                monthArray[4] = "May";
                monthArray[5] = "June";
                monthArray[6] = "July";
                monthArray[7] = "August";
                monthArray[8] = "September";
                monthArray[9] = "October";
                monthArray[10] = "November";
                monthArray[11] = "December";
                for (m = 0; m <= 11; m++) {
                    var optn = document.createElement("OPTION");
                    optn.text = monthArray[m];
                    // server side month start from one
                    optn.value = (m + 1);
                    // if june selected
                    if (m == 1) {
                        optn.selected = true;
                    }
                    month.options.add(optn);
                }
            }

            function visible() {
                var elem = document.getElementById('profileVisibility');
                if (elem) {
                    if (elem.innerHTML == "Switch to visible") {
                        elem.innerHTML = "Switch to invisible"
                    } else {
                        elem.innerHTML = "Switch to visible"
                    }
                }
            }

            var openFile = function(event) {
                var input = event.target;

                // Instantiate FileReader
                var reader = new FileReader();
                reader.onload = function() {
                    imageFile = reader.result;

                    document.getElementById("imageChange").innerHTML = '<img width="200" src="' + imageFile +
                        '" class="rounded-circle w-100 shadow" />';
                };
                reader.readAsDataURL(input.files[0]);
            };
        </script>
