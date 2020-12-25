<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('books')->insert([
            'isbn' => '9789574837939',
            'title' => '作業系統概念, 9/e (授權經銷版) (Operating System Concepts, 9/e)',
            'author' => 'Abraham Silberschatz、Peter B. Galvin、Greg Gagne 著、駱詩軒 譯',
            'publisher' => '',
            'publication_date' => Carbon::create(2014, 9, 14),
            'summary' => '  恐龍版作業系統一直以來都是作業系統的最佳教材。

在第九版中，除了嚴謹的理論基礎之外，還加入了非常多，也非常重要的實作。

對於學習作業系統的學生而言，常常只是瞭解理論，但卻苦於沒有動手實作的機會，或是不知道如何將理論應用於實際的作業系統。因此，如果能夠透過本書的學習，將理論與實際配合，對於學習作業系統上的幫助必然可以想見。

以譯者的觀點，學習作業系統時，除了理論與演算法之外，如果能實際地使用虛擬機器、在虛擬機器上執行一個作業系統，甚至進而修改作業系統並與理論配合，對於學習者而言才不會淪為紙上談兵。

因此如果想真的學好作業系統，就配合本書，到網站下載虛擬機器，執行書中的Linux作業系統，然後按部就班的循序漸進，必然更能理解許多理論與演算法的精闢。

除了實作之外，本書加入了：

多核心系統與平行運算

行動系統，包含了 : iOS和Android、虛擬機器、Windows 7






    目錄大綱

      第一篇　總　論

CHAPTER 1　概　　說

CHAPTER 2　系統結構

第二篇　行程管理

CHAPTER 3　行程觀念

CHAPTER 4　多執行緒

CHAPTER 5　行程排班

CHAPTER 6　同　步

CHAPTER 7　死　結

第三篇 記憶體管理

CHAPTER 8　記憶體管理策略

CHAPTER 9　虛擬記憶體管理

第四篇 儲存裝置

CHAPTER 10　檔案系統

CHAPTER 11　檔案系統的製作

CHAPTER 12　輔助儲存結構

CHAPTER 13　輸入/輸出系統

第五篇 保護和安全

CHAPTER 14　系統保護

CHAPTER 15　系統安全性

第六篇 個案研究

CHAPTER 16　Linux系統

CHAPTER 17　Windows

CHAPTER 18　影響的作業系統',
            'img_src' => '',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
