<?php

class Mahasiswa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mahasiswa_model');
    }

    public function index()
    {
        $data['judul'] = 'Halaman Mahasiswa';
        $data['mahasiswa'] = $this->Mahasiswa_model->getAllMahasiswa();
        if($this->input->post('keyword')){
            $data['mahasiswa'] = $this->Mahasiswa_model->cariDataMahasiswa();
        }
        $data['jurusan'] = $this->Mahasiswa_model->getAllJurusan();
        
        // Form validation rules
        $this->form_validation->set_rules('kode', 'Kode', 'required|is_unique[mahasiswa.kode]');
        $this->form_validation->set_rules('matakuliah', 'Mata Kuliah', 'required|is_unique[mahasiswa.matakuliah]');
        $this->form_validation->set_rules('sks', 'SKS', 'required|is_unique[mahasiswa.sks]');
        $this->form_validation->set_rules('semester', 'Semester', 'required|is_unique[mahasiswa.semester]');
        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required');

        if ($this->form_validation->run() == false) {
            // Load views with validation errors
            $this->load->view('templates/header', $data);
            $this->load->view('mahasiswa/index', $data);
            $this->load->view('templates/footer');
        } else {
            // Data to insert
            $data = [
                'kode' => $this->input->post('kode', true),
                'matakuliah' => $this->input->post('matakuliah', true),
                'sks' => $this->input->post('sks', true),
                'semester' => $this->input->post('semester', true),
                'jurusan' => $this->input->post('jurusan', true),
            ];
            
            // Insert data into database
            $this->db->insert('mahasiswa', $data);

            // Set flashdata for feedback to user
            $this->session->set_flashdata('flash', 'Data mahasiswa berhasil ditambahkan!');
            redirect('Mahasiswa');
        }
    }

    public function ubah()
    {
        // Load data mahasiswa yang ingin diubah berdasarkan id
        $this->Mahasiswa_model->ubahDataMahasiswa($id);
        
        // Set flashdata for feedback to user
        $this->session->set_flashdata('flash', 'Data mahasiswa berhasil diubah!');
        redirect('Mahasiswa');
    }

    public function hapus($id)
    {
        // Delete data mahasiswa berdasarkan id
        $this->Mahasiswa_model->hapusDataMahasiswa($id);

        // Set flashdata for feedback to user
        $this->session->set_flashdata('flash', 'Data mahasiswa berhasil dihapus!');
        redirect('Mahasiswa');
    }
}
