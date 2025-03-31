                <h3>
                    Cấu trúc điều khiển</h3>
                <p>
                    Blade cung cấp các shortcuts thuận tiện cho các cấu trúc điều khiển PHP phổ biến như
                    câu lệnh điều kiện và vòng lặp. Ví dụ:
                </p>

                <p><code>Câu lệnh điều kiện: @if, @elseif, @else, @endif
                    </code>
                </p>
                <p><code>Vòng lặp: @foreach, @endforeach
                    </code></p>
                <p><code>Hiển thị biến: {{ '{{' }} $variable {{ ' ?>' }}' }}</code></p>

                <h3>Bao gồm Subviews</h3>
                <p>
                    Directive @include cho phép bạn bao gồm một Blade view từ bên trong một view khác:
                </p>

                <p><code>@include('shared.errors')</code></p>

                <p>
                    Bạn cũng có thể truyền một mảng dữ liệu bổ sung cho view được bao gồm:
                </p>

                <p><code>@include('view.name', ['status' => 'complete'])</code></p>

                <h3>Hiển thị dữ liệu</h3>
                <p>
                    Bạn có thể hiển thị dữ liệu được truyền cho Blade view bằng cách bao quanh biến trong dấu ngoặc
                    nhọn:
                </p>

                <p><code>Hiển thị biến: &lt;h1&gt;Xin chào, @{{ $userName }}&lt;/h1&gt;</code></p>

                <p>
                    Bạn cũng có thể hiển thị kết quả của các hàm PHP:
                </p>

                <p><code>Thời gian hiện tại là @{{ time() }}</code></p>

                <p>
                    Trong view, bạn có thể kiểm tra nếu status message tồn tại và hiển thị nó:
                </p>

                <p><code>Kiểm tra session: @if (session('status'))
                            ... hiển thị thông báo ...
                        @endif
                    </code></p>
