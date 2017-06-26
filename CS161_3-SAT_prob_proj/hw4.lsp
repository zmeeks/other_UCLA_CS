;HW4 Zack Meeks XXXXXX115

; this is the main calling function. it initializes sath_1 function as well as the
; printing of the result

(defun sat? (n delta)
	(set 'sat-h (sath_1 (list delta NIL)))
	(printer1 (print_dfs sat-h) n))				
	
	
; this trivial function returns the absolute value	
	
(defun abs-val (val)
	(cond ((< val 0) (* val -1))
	(t val)))
	
	
(defun printer1 (list1 n)
	(cond ((null list1) NIL)					
	((atom (caar list1)) (printer2 (second list1) n))
	(t (printer1 (first list1) n))))
		

; I called it list2 in the function below to distinguish it from above and the fact that I
; took the cdr of the list so as to pass in the sat list as opposed to the cnf list or something else
	
(defun printer2 (list2 n)
	(cond ((equal (length list2) n) list2)
	((equal (first list2) 1) (cons 1 (printer3 n 1 (rest list2))))
	((equal (first list2) -1) (cons -1 (printer3 n -1 (rest list2))))
	(t (cons 1 (printer3 n 1 list2))))) 
	
	
; this prints the sat list	

(defun printer3 (n prev list1)
	(cond ((and (null list1) (equal (abs-val prev) n)) nil)
	((null list1) (cons (+ (abs-val prev) 1) (printer3 n (+ (abs-val prev) 1) nil)))			
	(t (cons (+ (abs-val prev) 1) (printer3 n (+ (abs-val prev) 1) list1)))))


; this function checks to see if there is a contradiction in the obtained one's list
; it returns T if there is a contradiction, else returns NIL

(defun alpha-con (c s)
	(cond ((null c) NIL)
	((null s) (alpha-con (cdr c) (cddr c)))
	((equal (first c) (* (first s) -1)) T)
	(t (alpha-con c (cdr s))))) 


; this is the main helper function to sath_1
; if there is no one's list then it proceeds to find the shortest list by calling
; dispatch function to handle this, else it need only return presat with the ones list
; inserted into it

(defun alpha (list1)
	(set 'cnf (first list1))
	(set 's (second list1))
	(cond ((alpha-con (set 'o_list (one-var cnf)) o_list) NIL)
	((null o_list) (dispatch cnf s))
	(t (list (ones-rmvr cnf) (presat_1 o_list s)))))


; sath_1 = sat helper function #1

; this is the first function called by the main function sat-?.
; if the cnf is null, then all of the terms can be satisfied in the original cnf (delta)

(defun sath_1 (list1)
	(cond ((null list1) NIL)
	((equal (caar list1) 0.5) list1)
	((null (first list1)) (list '(0.5) (second list1)))
	((and (equal (length list1) 2) (equal (caar list1) 0)) NIL)			
	((equal (caaar list1) 0) (sath_1 (rest list1)))
	((atom (caaar list1)) (sath_2 (alpha list1)))
	(t (cons (sath_1 (first list1)) (sath_1 (rest list1))))))
					

; this function will be the other main dispatching function along with sath_1.
; omega is this function's counterpart to sath_1's alpha
; if it finds a contradiction it will return a NIL, wherein the sath_1 function will
; clean up the nils and reduce the degrees

(defun sath_2 (list1)
	(cond ((null list1) nil)
	((equal (caar list1) 0.5) list1)
	((null (car list1)) (list '(0.5) (second list1)))									
	((null (caar list1)) (cons (sath_2 (first list1)) (sath_2 (rest list1))))    		
	(t (cons (sath_2 (first list1)) (sath_2 (rest list1))))))
	
	
(defun sath_3 (cnf s)
	(set 'simpd (simplify_1 cnf s))				
	(set 'simper (rmv-cons_1 simpd s))
	(set 'sim (zero-check simper))
	(cond ((null simpd) (list '(0.5) s))
	(t (list sim s))))
		

;cnf is what remains of the cnf after recursive parsing
;one-var returns a list of all the remaining one-variables yet to be assigned a truth-value
;the list that is returned is used by contradiction function to test unsat

(defun one-var (cnf)
	(cond ((null cnf) NIL)
	((equal (length (first cnf)) 1) (append (first cnf) (one-var (rest cnf))))
	(t (one-var (rest cnf)))))


; this function removes duplicates in the presat list

(defun dupl-rmvr (list1)
	(cond ((null list1) nil)
	((equal (first list1) (second list1)) (dupl-rmvr (cons (first list1) (cddr list1))))
	(t (cons (first list1) (dupl-rmvr (rest list1))))))


;presat_1 and presat_2 build the presat list in numerical order

(defun presat_1 (onesL presat)
	(set 'inord (reorder1 onesL))
	(cond ((null presat) (presat_2 (rest inord) (list (first inord))))			
	(t (dupl-rmvr (presat_2 inord presat)))))
	
	
(defun presat_2 (onesL presat)
	(cond ((null onesL) presat)
	((null presat) onesL) 
	((<= (abs-val (first onesL)) (abs-val (first presat))) (presat_2 (rest onesL) (cons (first onesL) presat)))
	(t (cons (first presat) (presat_2 onesL (rest presat))))))
		

; testing for unsat represents a forward-checking feature of this solution
; cnf is what remains of the cnf, presat is the working partial answer
; if this function finds a contradiction it will return T (true),

; ***** note: this function tests contradiction with presat list and ones list, while
; alpha-con checks for a contradiction in the ones-list itself ******

; ***** NOTE: returns NIL if no contradiction, returns T if there exists a contradiction

(defun con_1 (cnf presat)
	(set 'oneL (one-var cnf)
	(con_2 oneL oneL presat)))

	
(defun con_2 (ones oneL presat)
	(cond ((null presat) NIL)
	((null ones) (con_2 oneL oneL (rest presat)))
	((equal (* (first ones) -1) (first presat)) T)
	(t (con_2 (rest ones) oneL presat))))

	
; smallest must get passed as the length of (length (first cnf))
; when initially called from the calling function
; this function is pretty straight forward -- used when there are no length 1 clauses

(defun length-checker (cnf smallest)
	(cond ((null cnf) smallest)
	((< (length (first cnf)) smallest) (length-checker (rest cnf) (length (first cnf))))
	(t (length-checker (rest cnf) smallest))))
	

; note: as it is, this function is pretty straight forward 
; however, as is, I am not sure it is actually needed
; update: this function is currently deprecated/ not useful 
	
;(defun rmv-nils (list1)											
;	(cond ((equal (length list1) 0) NIL) 
;	((null (first list1)) (rmv-nils (rest list1)))
;	((atom (caar list1)) (cons (first list1) (rmv-nils (rest list1))))
;	(t (rmv-nils (cons (rmv-nils (first list1)) (rmv-nils (rest list1)))))))
	
	
; the two following functions are called by function sath3
; they delete clauses from the cnf that contain atoms already in the presat
; 
; ***** note: if this function returns NIL that means it is solveable, which needs to
; be distinguished from a resultant NIL from the remove contradicters functions *****
	
(defun simplify_2 (clause o_clause  presat o_presat)	
	(cond ((null presat) (simplify_2 (rest clause) o_clause o_presat o_presat))				
	((null clause) o_clause)
	((> (abs-val (first clause)) (abs-val (first presat))) (simplify_2 (rest clause) o_clause o_presat o_presat))	
	((equal (first clause) (first presat)) NIL)
	(t (simplify_2 clause o_clause (rest presat) o_presat))))
	
	
; delete clauses with atom in presat list	

(defun simplify_1 (cnf presat)
	(set 'cnf_clause (simplify_2 (first cnf) (first cnf) presat presat))
	(cond ((null cnf) nil)
	((null cnf_clause) (simplify_1 (rest cnf) presat))
	(t (cons cnf_clause (simplify_1 (rest cnf) presat)))))	


; returns T if it finds a nil -- note I don't think this function is needed bc simplify_1 should take care of it
;		****** ****** actually this function is useful to find NILs as a result of remove contradicters functions

(defun nil-finder (list1)
	(cond ((equal (length list1) 0) NIL) 
	((equal (length (first list1)) 0) T)
	(t (nil-finder (rest list1)))))
	

; these functions remove the atomic terms that conflict with the atoms in the presat list
; note: if the removal of contradicters results in an empty list, then that presat is 
; null and the cnf with those presat values is unsat which is later handled by sath_3 function

(defun rmv-cons_3 (clause presat)
	(cond ((null clause) NIL)
	((null presat) clause)
	((equal (* (first clause) -1) (first presat)) (rmv-cons_3 (rest clause) presat))
	(t (cons (first clause) (rmv-cons_3 (rest clause) presat)))))
	
	
(defun rmv-cons_2 (clause presat)
	(cond ((null presat) clause)
	(t (rmv-cons_2 (rmv-cons_3 clause presat) (rest presat)))))

	
(defun rmv-cons_1 (cnf presat)
	(set 'cnf_clause (rmv-cons_2 (first cnf) presat))			
	(cond ((null cnf) NIL)											
	((null cnf_clause) '((0)))										
	(t (cons cnf_clause (rmv-cons_1 (rest cnf) presat)))))			


; returns first clause with length k

(defun copy_len_k_clause (cnf k)
	(cond ((equal k (length (first cnf))) (first cnf))
	(t (copy_len_k_clause (rest cnf) k))))


; returns cnf minus first clause with length k

(defun del_len_k_clause (cnf k) 
	(cond ((equal k (length (first cnf))) (rest cnf))
	(t (cons (first cnf) (del_len_k_clause (rest cnf) k)))))
		
	
; these are the two main helper functions to the function alpha
	
(defun dispatch_2 (cnf s term)											
	(cond ((equal (length term) 0) NIL) 								
	(t (cons (list (verify cnf (insert_1 (first term) s)) (insert_1 (first term) s)) (dispatch_2 cnf s (rest term))))))
		

(defun dispatch (cnf s)
	(set 'k (length-checker cnf (length (first cnf))))
	(set 'k_clause (copy_len_k_clause cnf k))
	(dispatch_2 (del_len_k_clause cnf k) s k_clause))
		
	
; inserts a single element into the proper place in a list
; this is useful (and faster than more general function) when need to insert atom from 
; a length one clause into presat
	
(defun insert_1 (ins list1)
	(cond ((null list1) (list ins))
	((< (abs-val ins) (abs-val (first list1))) (cons ins list1))
	((equal (* ins -1) (first list1)) (cons 0.1 list1))						
	((equal (abs-val ins) (abs-val (first list1))) list1)					
	(t (cons (first list1) (insert_1 ins (rest list1)))))) 


; this removes all the ones from the cnf list. 
; this is called after finding the list of such ones

(defun ones-rmvr (cnf)
	(cond ((null cnf) NIL)
	((equal (length (first cnf)) 1) (rest cnf))
	(t (cons (first cnf) (ones-rmvr (rest cnf))))))


;Returns total 

(defun nested-nils (list0)						
	(cond 
		((null list0) 0)
		((and (atom list0) (not (null list0))) 1)
		((and (null (first list0)) (null (rest list0))) 0)
		((not (listp (first list0))) 1)
		((not (listp (rest list0))) 1)
		(t (+ (nested-nils (first list0)) (nested-nils (rest list0))))))						
	
	
; These functions reorder the ones list so that the presat functions can append the remaining chunk without having to do anymore parsing
; I honestly wouldn't have gone for this fix if I planned out the function better, but in hindsight or fortuity I think these functions
; might have a better time complexity than a cleaner version would have because it doesn't need to do as many checks and seemingly makes the
; same number as passes, if not less, through the data than the cleaner bubblesort version that I would have otherwise coded
	
(defun reorder1 (list1)
	(cond ((null list1) list1)
	((equal (length list1) 1) list1)
	(t (reorder2 (list (first list1)) (rest list1)))))
	
	
(defun reorder2 (list1 list2)
	(cond ((null list2) list1)																		
	((null list1) (list (first list2)))
	((< (abs-val (first list2)) (abs-val (first list1))) (reorder2 (cons (first list2) list1) (rest list2))) 		
	
	
(defun zero-check (cnf)								
	(cond 
		((equal (caar (last cnf)) 0) '(0))                   
		(t cnf))) 	
	
	
(defun print_dfs (list1)																	
	(cond 
		((equal (length list1) 0) NIL)
		((equal (nested-nils list1) 0) NIL)
		((equal (nested-nils (first list1)) 0) (print_dfs (rest list1)))
		((equal (nested-nils (caar list1)) 0) (print_dfs (cons (cdar list1) (rest list1))))
		((atom (caar list1)) list1)
		((equal (nested-nils (rest list1)) 0) (print_dfs (first list1)))									
		(t (cons (print_dfs (first list1)) (print_dfs (rest list1))))))	
	
	
(defun verify (cnf presat)
	(cond
		((null presat) cnf)
		((equal (first presat) 0.1) '(0))
		(t (verify cnf (rest presat)))))
	
	
; added: the parser_cnf.lsp funcs:

(defun split-line (line)
  (if (equal line :eof)
      :eof
      (with-input-from-string (s line) (loop for x = (read s nil) while x collect x))))


(defun read-cnf (filename)
  (with-open-file (in filename)
    (loop for line = (split-line (read-line in nil :eof)) until (equal line :eof)
      if (equal 'p (first line)) collect (third line)      ; var count
      if (integerp (first line)) collect (butlast line)))) ; clause


(defun parse-cnf (filename)
  (let ((cnf (read-cnf filename))) (list (car cnf) (cdr cnf))))


; Following is a helper function that combines parse-cnf and sat?

(defun solve-cnf (filename)
  (let ((cnf (parse-cnf filename))) (sat? (first cnf) (second cnf))))
 
