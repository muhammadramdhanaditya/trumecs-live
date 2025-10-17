<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function getsetting($value)
    {
        $this->db->where('name',$value)
                ->from('setting');        
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getnamecategori($value){
        $this->db->where("id", $value)
                ->select("name")
                ->from("categori");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getnamequality($value){
        $this->db->where("id", $value)
                ->select("grade")
                ->from("grade");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getidcategori($value)
    {
        $this->db->where("url", $value)
                ->from("categori");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getpromo($value)
    {
        $promo = $this->db->where("id",$value)->from("promo")->get();
        $getproduct = $promo->result_array();
        if (count($getproduct)>0) {
            $product = $getproduct[0]["product"];
            $array_product = explode(",", $product);
            $select = $this->db->select("");
            $i=0;$asb="";
            foreach ($array_product as $key => $v) {
                    /*!empty($key)?
                    $this->db->or_like_in("tittle",$key) : "";*/
                    $asb .= " OR id='".$v."'";
                }
                $getselect = $select->where("( id = '' ".$asb.")")->from("product")->get()->result_array();
                $arrayall = array_merge(array('product' => $getselect),array('promo' => $getproduct));
                return $arrayall;
            }

        }

        public function getcucigudang($value)
    {
        $promo = $this->db->where("id",$value)->from("cucigudang")->get();
        $getproduct = $promo->result_array();
        if (count($getproduct)>0) {
            $product = $getproduct[0]["product"];
            $array_product = explode(",", $product);
            $select = $this->db->select("");
            $i=0;$asb="";
            foreach ($array_product as $key => $v) {
                    /*!empty($key)?
                    $this->db->or_like_in("tittle",$key) : "";*/
                    $asb .= " OR id='".$v."'";
                }
                $getselect = $select->where("( id = '' ".$asb.")")->from("product")->get()->result_array();
                $arrayall = array_merge(array('product' => $getselect),array('cucigudang' => $getproduct));
                return $arrayall;
            }

        }

    public function record_count($datasearch,$datasearchor_like,$datawhere) {
            
        $query= [];
            
        if (!empty($datasearchor_like["tittle"]) or ($datasearchor_like["tittle"])!="") {
            $query = $this->get_query($datasearchor_like["tittle"]);
        };

        if (!empty($datawhere["brand"])) {
            $this->db->where('brand',$datawhere["brand"]);
        }
        if (!empty($datawhere["type"])) {
            $this->db->where('type',$datawhere["type"]);
        }
        if (!empty($datawhere["component"]) ) {
            $this->db->where("component",$datawhere["component"]);
        }
        if (!empty($datawhere["year"]) ) {
            $this->db->where("year",$datawhere["year"]);
        }
        if (!empty($datawhere["promo"]) ) {
            $this->db->where("promo",$datawhere["promo"]);
        }
        if (!empty($datawhere["cucigudang"]) ) {
            $this->db->where("cucigudang",$datawhere["cucigudang"]);
        }

        if (!empty($datawhere["quality"]) ) {
            $this->db->where("quality",$datawhere["quality"]);
        }

        if (!empty($datasearch["minp"]) AND !empty($datasearch["maxp"]) AND ($datasearch["minp"]!="")) {
            $minp = ($datasearch["minp"]=="0") ? 1 : $datasearch["minp"];
            $this->db->where("price BETWEEN ".$minp." AND ".$datasearch["maxp"]);
        }


        if (!empty($datasearchor_like["tittle"]) or ($datasearchor_like["tittle"])!="") {
            $this->db->group_start();
                $this->db->group_start();
                    $this->db->or_like("tittle",$datasearchor_like["tittle"]);
                    $this->db->or_like("partnumber",$datasearchor_like["partnumber"]);
                    $this->db->or_like("physicnumber",$datasearchor_like["physicnumber"]);
                $this->db->group_end();
                if($query){
                $this->db->or_group_start();
                    $this->db->or_where_in('brand',$query);
                    $this->db->or_where_in('type',$query);
                    $this->db->or_where_in('component',$query);
                $this->db->group_end();
                }
            $this->db->group_end();
        };

        
        $query = $this->db->where("status","show")
        ->from("product");
        return $query->get()->num_rows();
    }

    public function fetch_lelang($limit, $start, $datasearch, $datasearchor_like, $datawhere, $rand = false) {
        
        $query= [];
        
        if (!empty($datasearchor_like["judul"]) or ($datasearchor_like["judul"])!="") {
            $query = $this->get_query($datasearchor_like["judul"]);
        };
        
        if (!empty($datawhere["category"])) {
            $sub_category = $this->get_sub_category($datawhere["category"]);
        };

        if (!empty($datawhere["category"]) ) {
            $this->db->where("category",$datawhere["category"]);
        }
        
        if (!empty($datasearchor_like["judul"]) or ($datasearchor_like["judul"])!="") {
            $this->db->group_start();
                $this->db->group_start();
                    $this->db->or_like("judul",$datasearchor_like["judul"]);
                    $this->db->or_like("uraian",$datasearchor_like["uraian"]);
                    $this->db->or_like("info_penjual",$datasearchor_like["info_penjual"]);
                $this->db->group_end();
                if($query){
                $this->db->or_group_start();
                    $this->db->or_where_in('category',$query);
                $this->db->group_end();
                }
            $this->db->group_end();
        };

        
        
        
        $this->db->limit($limit, $start)->where("status","show")
        ->order_by("id","DESC");
        
        
        $query = $this->db->get("lelang");

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = (array) $row;
            }
            return  $data;

        }

        return false;
    }

    private function get_sub_category($id_category) {
        $this->db->select('id');
        $this->db->where('parent', $id_category);
        $data = $this->db->get('categori');

        $children = [];
        $children[] = $id_category;
        foreach($data->result() as $item):
            //$children[] = $item->id;
            $sub_children = $this->get_sub_category($item->id);
            $children = array_merge($children, $sub_children);
        endforeach;

        return $children;
    }

    public function get_category() {
        $this->db->where('parent', '0');
        $data = $this->db->get('categori');

        return $data;
    }

    public function get_query($string) {
        $string = explode(" ", $string);

        $this->db->select('id');
        foreach($string as $item):
            $this->db->or_like("name", $item, "both");
        endforeach;

        $data = $this->db->get('categori');

        $children = [];
        foreach($data->result() as $item):
            $sub_children = $this->get_sub_category($item->id);
            $children = array_merge($children, $sub_children);
        endforeach;

        return $children;
    }

    public function fetch_product_by_cat($limit, $start, $datasearch, $datasearchor_like, $datawhere, $rand = false) {
        $cat = $this->get_category();
        $data = array();
        foreach($cat->result() as $key=>$item) {
            $query = [];

            if (!empty($datasearchor_like["tittle"]) or ($datasearchor_like["tittle"])!="") {
                $query = $this->get_query($datasearchor_like["tittle"]);
            };
            
            //if (!empty($datawhere["component"])) {
                $sub_category = $this->get_sub_category($item->id);
            //};
            
            if (!empty($datawhere["brand"])) {
                $this->db->where('brand',$datawhere["brand"]);
            } 

            if (!empty($datawhere["type"])) {
                $this->db->where('type',$datawhere["type"]);
            }

            //if (!empty($datawhere["component"]) ) {
                $this->db->where_in("component", $sub_category);
            //}

            if (!empty($datawhere["year"]) ) {
                $this->db->where("year",$datawhere["year"]);
            }

            if (!empty($datawhere["promo"]) ) {
                $this->db->where("promo",$datawhere["promo"]);
            }

            if (!empty($datawhere["cucigudang"]) ) {
                $this->db->where("cucigudang",$datawhere["cucigudang"]);
            }

            if (!empty($datawhere["quality"]) ) {
                $this->db->where("quality",$datawhere["quality"]);
            }

            if (!empty($datasearch["minp"]) AND !empty($datasearch["maxp"]) AND ($datasearch["minp"]!="") ) {
                $minp = ($datasearch["minp"]=="0") ? 1 : $datasearch["minp"];
                $this->db->where("price BETWEEN ".$minp." AND ".$datasearch["maxp"]."");
            }

            
            if (!empty($datasearchor_like["tittle"]) or ($datasearchor_like["tittle"])!="") {
                $this->db->group_start();
                    $this->db->group_start();
                        $this->db->or_like("tittle",$datasearchor_like["tittle"]);
                        $this->db->or_like("partnumber",$datasearchor_like["partnumber"]);
                        $this->db->or_like("physicnumber",$datasearchor_like["physicnumber"]);
                    $this->db->group_end();
                    if($query){
                    $this->db->or_group_start();
                        $this->db->or_where_in('brand',$query);
                        $this->db->or_where_in('type',$query);
                        $this->db->or_where_in('component',$query);
                    $this->db->group_end();
                    }
                $this->db->group_end();
            };

            
            //if($rand) {
                $this->db->limit($limit, $start)->where("status","show")
                ->order_by("RAND()","", false);
            //} else {
            //    $this->db->limit($limit, $start)->where("status","show")
            //    ->order_by("id","DESC");
           // }
           

            $query = $this->db->get("product"); 

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $data[$item->id][] = (array) $row;
                }
                
                
            }
        }

        return  $data;
    }

    public function getlelang($url)
    {
        $query = $this->db->where("lelang.id",$url)->get('lelang');
        
        $return = $query->result_array();
        
        return $return;
    }
    public function getgalery($value)
    {
        $query = $this->db->where("lelang",$value)->get('galery_lelang');
        return $query->result_array();
    }
    public function getsamelelang($value,$id)
    {
        $array= array();
        $asb = '';
        $akhir=count($value)-1;
        $this->db->limit(18)
                ->from("lelang");

                foreach ($value as $key => $v) {
                    $ses= $this->db->escape_str($v);
                    $asb .= " or judul LIKE '%".$ses."%'";
                }

        $this->db->where_not_in("id",$id);
        $this->db->where("( id = '' ".$asb.") AND status='show'");
        $this->db->order_by("id","desc");
        $query = $this->db->get();
        return $query->result_array();
    }
}