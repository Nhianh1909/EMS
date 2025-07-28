Các lưu ý dành cho team nhé: 
1. Các bước khởi tạo nhánh (❌phần này tham khảo vì Antony đã tạo r❌):
   - git checkout -b frontend
   - git push -u origin frontend
   
   - git checkout main
     
   - git checkout -b backend
   - git push -u origin backend
     
   ⏭️Sau khi tạo nhánh xong nhớ checkout main để quay về nhánh chính nhé




2. Quy trình làm việc nhóm😍
   
   (LƯU Ý CHO CẢ BACKEND VÀ FRONTEND TEAM. DÙNG LỆNH GIT BRANCH ĐỂ KIỂM TRA XEM MÌNH ĐANG Ở NHÁNH NÀO ĐẦU TIÊN R HÃY TIẾN HÀNH NHÉ)
   
   # Về team frontend
     + git checkout frontend
     + git pull origin frontend (Cả 2 bạn đều làm bước này trước khi code) (bước này kt xem có thay đổi gì ko. Nếu hiện already up to date thì oke)
       
   🤦‍♂️Vd bạn K làm xong trang login.php
     + git checkout -b frontend_login (tạo nhánh khác)
     + git add <tên file hoạc đường dẫn file nếu nằm trong thư mục>
     + git commit -m"Tạo giao diện trang login.php"
     + git push origin frontend_login
       
   🤦‍♂️Vd bạn L làm xong trang register.php
     + git checkout -b frontend_register (tạo nhánh khác)
     + git add <tên file hoạc đường dẫn file nếu nằm trong thư mục>
     + git commit -m"Tạo giao diện trang register.php"
     + git push origin frontend_register
       
   🫡Sau khi push xong có 2 nhánh 2 file đó bạn L sẽ có nhiệm vụ như sau để merge lại
   
     + git checkout frontend
     + git pull origin frontend
     #  Merge nhánh login trước
     + git merge frontend_login
     + Nếu không có conflict → OK
     #  Merge nhánh register tiếp
     + git merge frontend_register
     + Nếu không có conflict → OK
       
     😤😤😤Ở trường hợp này sẽ xuất hiện mọt cái bảng soạn thảo VIM. nếu ko có thay đổi gì thì bấm esc -> :wq để thoát😤😤😤

     # Push lại nhánh frontend đã được gộp đủ
     + git push origin frontend
     + Sau đó lên git ktra nhé  
     
   📝📝📝📝📝📝📝📝LƯU Ý📝📝📝📝📝📝📝📝📝📝📝
   
   📝 - TEAM FRONTEND LÀM XONG FILE NÀO THÌ PHẢI PUSH LÊN FILE ĐÓ VÀ BẠN L SẼ CÓ TRÁCH NHIỆM MERGE LẠI  
   📝 TOÀN BỘ FRONTEND SAU KHI PUSH  
   📝 QUY TRÌNH MERGE NHƯ SAU:  
   📝+ git checkout frontend  
   📝+ git pull origin frontend  
   📝  
   📝+ git merge frontend/login   
   📝+ git merge frontend/register  
   📝+ git push origin frontend  
   📝NHỚ LÊN GIT VỪA LÀM VỪA KT NHÉ  
   📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝  


    # Team backend
    - Đầu tiên, để backend có những file mà frontend đã merge, bạn cần merge nhánh frontend vào backend:
      + git checkout backend  
      + git pull origin backend  
      + git merge origin/frontend   # ⚠️ Chỉ cần 1 người merge 1 lần này để có tất nhánh frontend  
        
    🤦‍♂️Vd bạn A làm xong chức năng trang login.php
     + git checkout -b backend_HandleLogin(tạo nhánh mới)  
     + git add (đường dẫn file vừa mới sửa. Nếu 1 file thì chỉ cần ghi 1 file)    
     + git commit -m"thêm chức năng trang login"  
     + git push origin backend/HandleLogin
       
   🤦‍♂️Vd bạn C làm xong trang register.php  
     + git checkout -b backend_HandleRegister(tạo nhánh khác)  
     + git add (đường dẫn file vừa mới sửa hoặc tên file nếu chỉ có 1 file)  
     + git commit -m"thêm chức năng trang register"  
     + git push origin backend_HandleRegister     
   📝📝📝📝📝📝📝📝LƯU Ý📝📝📝📝📝📝📝📝📝📝📝  
   📝  TEAM BACKEND LÀM XONG FILE NÀO THÌ PHẢI PUSH LÊN FILE ĐÓ VÀ BẠN A SẼ CÓ TRÁCH NHIỆM MERGE LẠI TOÀN BỘ BACKEND SAU KHI PUSH  
   📝 QUY TRÌNH MERGE NHƯ SAU:  
   📝+ git checkout backend  
   📝+ git pull origin backend  
   📝
   📝+ git merge backend/HandleLogin  
   📝+ git merge backend/HandleRegister  
   📝+ git push origin backend  
   📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝  

    # Bước kiểm tra sản phẩm và chức năng (Bước này chỉ có Antony và C test)
      Sau khi 2 team đã làm xong 2 giao diện và 2 chức năng của giao diện đó thì Antony sẽ test xem đã đúng luồng chưa  
     # Bắt đầu từ main để tạo base  
        git checkout main
        git pull origin main (kt xem có sự thay đổi gì ko)  
     # Tạo nhánh test để gộp frontend và backend lại 
        git checkout -b test  
        git branch (để xem đang nhánh nào)  
     # Kiểm tra xem ai đã chỉnh sửa file nào  
        git blame <tên file>
     # Xem toàn bộ lịch sử của một file
        git log <tên-file>
     # Kiểm tra nhánh nào đã merge vào nhánh hiện tại
        git log --merges
     # Kiểm tra xem có sự khác biệt nào giữa 2 nhánh
        git diff frontend..backend (vd)
     # Kiểm tra conflict trước khi merge  
        git fetch origin (lấy toàn bộ nhánh mới nhất từ remote nhưng ko tự động merge vào nhánh hiện tại)
        git checkout frontend (chuyển nhánh muốn kiểm tra)
        git merge backend --no-commit --no-ff (Thứ merge backend vào frontend)
        Nếu test xong oke thì git commit...
        Nếu muốn hủy merge mà chưa muốn gộp thì git merge --abort  
     # Merge nhánh frontend
        git merge origin/frontend  
     # Merge nhánh backend
        git merge origin/backend  
     # Nếu có xung đột tính sau
<<<<<<< .merge_file_xnk2EW
        git push origin test
     # Note thêm: lệnh xóa nhánh trong local
        git branch -D <nhánh>
        git status
        git log --oneline --decorate --graph
=======
        git push origin integration-test
>>>>>>> .merge_file_VMwFGl


       

   
   
