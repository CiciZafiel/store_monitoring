<template>
    <div>
        <div class="container-fluid pb-3">
            <div class="d-grid gap-3" style="grid-template-columns: 1fr;">
                <div class="bg-body-tertiary border rounded-3 card">
                    <div class="card-header">
                        <h2>Store Name</h2>
                    </div>
                    <table class="table table-hover table-striped table-dark">
                        <thead>
                            <th>Item Code</th>
                            <th>IMEI</th>
                            <th>IMEI Status</th>
                            <th>Exist</th>
                        </thead>
                        <tbody>
                            <td>TestCode</td>
                            <td>TestIMEI</td>
                            <td><button class="btn btn-danger">Not Available</button></td>
                            <td><button class="btn btn-success">Existing</button></td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>