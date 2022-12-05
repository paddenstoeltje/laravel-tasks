@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    New Order
                </div>

                <div class="panel-body">
                    <!-- Display Validation Errors -->
                    @include('common.errors')

                    <!-- New Order Form -->
                    <form action="/task" method="POST" class="form-horizontal">
                        {{ csrf_field() }}

                        <!-- Order ID -->
                        <div class="form-group">
                            <label for="orderid" class="col-sm-3 control-label">Order ID</label>
                            <div class="col-sm-6">
                                <input type="text" name="orderid" id="orderid" class="form-control" value="{{ old('task') }}">
                            </div>
                        </div>

                        <!-- Milling Time -->
                        <div class="form-group">
                            <label for="milling" class="col-sm-3 control-label">Milling Time</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="milling" id="milling">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>

                        <!-- Drilling Time -->
                        <div class="form-group">
                            <label for="milling" class="col-sm-3 control-label">Drilling Time</label>
                            <div class="col-sm-6">
                                <input type="text" name="drilling" id="drilling" class="form-control" value="{{ old('task') }}">
                            </div>
                        </div>

                        <!-- productionplant -->
                        <div class="form-group">
                            <label for="plant" class="col-sm-3 control-label">Production plant</label>
                            <div class="col-sm-6">
                                <input type="text" name="plant" id="plant" class="form-control" value="{{ old('task') }}">
                            </div>
                        </div>

                        <!-- Add Task Button -->
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-btn fa-plus"></i>Add Order
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Tasks -->
            @if (count($tasks) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Current Orders
                    </div>

                    <div class="panel-body">
                        <table class="table table-striped task-table">
                            <thead>
                                <th>Order ID</th>
                                <th>Milling Time</th>   
                                <th>Drilling Time</th>   
                                <th>Plant #</th>                                                                                                
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    <tr>
                                        <td class="table-text"><div>{{ $task->orderid }}</div></td>
                                        <td class="table-text"><div>{{ $task->milling}}</div></td>
                                        <td class="table-text"><div>{{ $task->drilling}}</div></td>
                                        <td class="table-text"><div>{{ $task->plant}}</div></td>                                                                                
                                        <!-- Task Delete Button -->
                                        <td>
                                            <form action="{{'/task/' . $task->id }}" method="POST">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}

                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-btn fa-trash"></i>Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            
        </div>
    </div>
@endsection
