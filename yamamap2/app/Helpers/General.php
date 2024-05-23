<?php

use Illuminate\Support\Arr;

function uploadImage($folder, $image)
{
  $extension = strtolower($image->extension());
  $filename = time() . rand(100, 999) . '.' . $extension;
  $image->getClientOriginalName = $filename;
  $image->move($folder, $filename);
  return $filename;
}
// function get_cols_where_p($model, $colums_names = array(), $where = array(),
//  $orderField, $orderType,)
function get_cols_where_p($model, $orderField, $orderType, $colums_names = array(), $where = array())
{
  if (!is_string($orderField) || !is_string($orderType)) {
    // يمكنك هنا التعامل مع القيم غير الصحيحة إذا كانت كذلك
    return null;
  }

  $data = $model::select($colums_names)->where($where)->orderby($orderField, $orderType)->paginate(PAGINATION_COUNT);
  return $data;
}


// function get_cols_where($model, $colums_names = array(), $where = array(), $orderField, $orderType,)
// function get_cols_where($model, $orderField, $orderType, $colums_names = [], $where = [])
// {
//     $data = $model::select($colums_names)->where($where)->orderby($orderField, $orderType)->get();
//     return $data;
// }

// ==================================
function get_cols_where($model, $orderField, $orderType, $colums_names = [], $where = [])
{
  // التحقق من أن $orderField و $orderType هما من نوع السلسلة (string)
  if (!is_string($orderField) || !is_string($orderType)) {
    // يمكنك هنا التعامل مع القيم غير الصحيحة إذا كانت كذلك
    return null;
  }

  $data = $model::select($colums_names)->where($where)->orderBy($orderField, $orderType)->get();
  return $data;
}

// ==================================

// function get_cols($model, $colums_names = array(), $orderField, $orderType,)
function get_cols($model, $orderField, $orderType, $colums_names = [])
{
  if (!is_string($orderField) || !is_string($orderType)) {
    // يمكنك هنا التعامل مع القيم غير الصحيحة إذا كانت كذلك
    return null;
  }

  $data = $model::select($colums_names)->orderby($orderField, $orderType)->get();
  return $data;
}
function get_cols_where_row($model, $colums_names = array(), $where = array())
{


  $data = $model::select($colums_names)->where($where)->first();
  return $data;
}
// function get_cols_where_row_orderBy($model, $colums_names = array(), $where = array(), $orderField, $orderType,)
function get_cols_where_row_orderBy($model, $orderField, $orderType, $colums_names = [], $where = [])
{
  if (!is_string($orderField) || !is_string($orderType)) {
    // يمكنك هنا التعامل مع القيم غير الصحيحة إذا كانت كذلك
    return null;
  }

  $data = $model::select($colums_names)->where($where)->orderby($orderField, $orderType)->first();
  return $data;
}
function get_field_value($model = null, $field_name = null, $where = array())
{
  $data = $model::where($where)->value($field_name);
  return $data;
}
function update($model, $data_to_update, $where = array())
{
  $flag = $model::where($where)->update($data_to_update);
  return $flag;
}
function delete($model, $where = array())
{
  $flag = $model::where($where)->delete();
  return $flag;
}
function insert($model, $arrayToInsert = array())
{
  $flag = $model::insert($arrayToInsert);
  return $flag;
}