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
     + git pull origin frontend (Cả 2 bạn đều làm bước này trước khi code)
       
   🤦‍♂️Vd bạn K làm xong trang login.php
     + git checkout -b frontend/login
     + git add frontend/login.php
     + git commit -m"Tạo giao diện trang login.php"
     + git push origin frontend/home
       
   🤦‍♂️Vd bạn L làm xong trang register.php
     + git checkout -b frontend/register
     + git add frontend/register.php
     + git commit -m"Tạo giao diện trang register.php"
     + git push origin frontend/register
       
   🫡Sau khi push xong có 2 nhánh 2 file đó bạn L sẽ có nhiệm vụ như sau để merge lại
   
     + git checkout frontend
     + git pull origin frontend
     #  Merge nhánh login trước
     + git merge frontend/login
     + Nếu không có conflict → OK
     #  Merge nhánh register tiếp
     + git merge frontend/register
     + Nếu không có conflict → OK
     # Push lại nhánh frontend đã được gộp đủ
     + git push origin frontend
       
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
   📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝📝  


    # Team backend
    - Đầu tiên, để backend có những file mà frontend đã merge, bạn cần merge nhánh frontend vào backend:
      + git checkout backend
      + git pull origin backend
      + git merge origin/frontend   # ⚠️ Chỉ cần merge 1 lần này
    🤦‍♂️Vd bạn A làm xong chức năng trang login.php
     + git checkout -b backend/HandleLogin
     + git add (đường dẫn file vừa mới sửa)
     + git commit -m"thêm chức năng trang login"
     + git push origin backend/HandleLogin
   🤦‍♂️Vd bạn C làm xong trang register.php
     + git checkout -b backend/HandleRegister
     + git add (đường dẫn file vừa mới sửa)
     + git commit -m"thêm chức năng trang register"
     + git push origin backend/HandleRegister  
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

    #Bước kiểm tra sản phẩm và chức năng (Bước này chỉ có Antony và C test)
   - Sau khi 2 team đã làm xong 2 giao diện và 2 chức năng của giao diện đó thì Antony sẽ test xem đã đúng luồng chưa
     # Bắt đầu từ main để tạo base
        git checkout main
        git pull origin main
     # Tạo nhánh test để gộp frontend và backend lại 
        git checkout -b integration-test
        git branch (để xem đang nhánh nào)
     # Merge nhánh frontend
        git merge origin/frontend
     # Merge nhánh backend
        git merge origin/backend
     # Nếu có xung đột tính sau
     git push origin integration-test


       

   
   
