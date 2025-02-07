<?php

  /**
   * 231232028 - Falmesino Abdul Hamid
   * Contoh sederhana Apriori untuk frequent 1-itemset dan 2-itemset
   */

   // Data transaksi: setiap transaksi merupakan array item
   $transactions = [
    ['susu', 'roti', 'keju'],
    ['roti', 'keju'],
    ['susu', 'roti'],
    ['roti', 'keju'],
    ['susu', 'keju']
   ];
   $minSupport = 0.6;
   $totalTransactions = count($transactions);

   // Fungsi untuk menghitung support suatu itemset
   function countSupport($transactions, $itemset) {
    $count = 0;
    foreach ($transactions as $trans) {
      if (count(array_intersect($trans, $itemset)) == count($itemset)) {
        $count++;
      }
    }
    return $count / count($transactions);
   }

   // Mendapatkan frequent 1-itemset
   function apriori1itemset($transactions, $minSupport) {
    $itemCounts = [];
    foreach ($transactions as $trans) {
      foreach ($trans as $item) {
        if (!isset($itemCounts[$item])) {
          $itemCounts[$item] = 0;
        }
        $itemCounts[$item]++;
      }
    }
    $frequent1 = [];

    foreach ($itemCounts as $item => $count) {
      $support = $count / count($transactions);
      if ($support >= $minSupport) {
        $frequent1[$item] = $support; // Gunakan string key
      }
    }
    return $frequent1;
   }

   // Mendapatkan kandidat 2-itemset dari frequent 1-itemset
   function generateCandidates($frequent1) {
    $items = array_keys($frequent1); // Ambil item dari frequent1
    $candidates = [];
    $n = count($items);
    for ($i = 0; $i < $n; $i++) {
      for ($j = $i + 1; $j < $n; $j++) {
        $candidate = [$items[$i], $items[$j]];
        sort($candidate); // Urutkan untuk konsistensi
        $candidates[] = $candidate;
      }
    }
    return $candidates;
   }

   // Evaluasi frequent 2-itemset
   function apriori2Itemset($transactions, $candidates, $minSupport) {
    $frequent2 = [];
    foreach ($candidates as $candidate) {
      $support = countSupport($transactions, $candidate);
      if ($support >= $minSupport) {
        $frequent2[implode(',', $candidate)] = $support; // Assign ke frequent2
      }
    }
    return $frequent2;
   }

   $frequent1 = apriori1itemset($transactions, $minSupport);
   $candidates2 = generateCandidates($frequent1);
   $frequent2 = apriori2Itemset($transactions, $candidates2, $minSupport);

   echo "Frequent 1-itemsets:\n";
   print_r($frequent1);
   echo "Frequent 2-itemsets:\n";
   print_r($frequent2);

   /**
    * Analisis:
    * - Proses frequent 1-itemset: O(n * k) dengan n transaksi dan k item
    *   rata-rata per transaksi.
    * - Proses kandidate 2-itemset: O(m^2) dengan m item unik.
    * - Support dihitung untuk tiap kandidat dengan proses iteratif.
    */
?>