<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_class" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New Class</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="20%">
					<col width="20%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">Sr.no.</th>
						<th class="text-center">Class</th>
						<th class="text-center">Section</th>
						<th class="text-center">Total Strength</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT *,concat(curriculum,'  ',level) as `class` FROM class_list order by class asc ");
					while ($row = $qry->fetch_assoc()) :
						// Get the class ID for the current row
						$class_id = $row['id'];
	
						// Query to count the number of students for the current class
						$student_count_query = "SELECT COUNT(*) AS total_students FROM student_list WHERE class_id = $class_id";
						$student_count_result = $conn->query($student_count_query);
	
						// Check if the query was successful
						if ($student_count_result) {
							// Fetch the row as an associative array
							$student_count_row = $student_count_result->fetch_assoc();
	
							// Access the total_students column from the result
							$total_students = $student_count_row['total_students'];
						} else {
							// If the query failed, set total_students to 0
							$total_students = 0;
						}			
						?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td class="text-center"><b><?php echo $row['class'] ?></b></td>
						<td class="text-center"><b><?php echo $row['section'] ?></b></td>
						<td class="text-center"><b><?php echo $total_students ?></b></td>
						<td class="text-center">
		                    <div class="btn-group">
		                        <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-primary btn-flat manage_class">
		                          <i class="fas fa-edit"></i>
		                        </a>
		                        <button type="button" class="btn btn-danger btn-flat delete_class" data-id="<?php echo $row['id'] ?>">
		                          <i class="fas fa-trash"></i>
		                        </button>
	                      </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
		$('.new_class').click(function(){
			uni_modal("New class","<?php echo $_SESSION['login_view_folder'] ?>manage_class.php")
		})
		$('.manage_class').click(function(){
			uni_modal("Manage class","<?php echo $_SESSION['login_view_folder'] ?>manage_class.php?id="+$(this).attr('data-id'))
		})
	$('.delete_class').click(function(){
	_conf("Are you sure to delete this class?","delete_class",[$(this).attr('data-id')])
	})
	})
	function delete_class($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_class',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>