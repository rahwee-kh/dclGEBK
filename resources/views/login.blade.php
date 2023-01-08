
@extends('layout')
  
@section('content')
<main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                
                  <div class="card-body text-center">
                    <button class="btn btn-primary mt-5" onclick="web3Login();">Log in with MetaMask</button>
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>
@endsection
<script>
    async function web3Login() {
        if (!window.ethereum) {
            alert('MetaMask not detected. Please install MetaMask first.');
            return;
        }
        

        const loginSignatureUrl = '{{ route('sign-user') }}'
        const loginUrl = '{{ route('auth-user') }}'
        const redirectUrl = '{{ route('dashboard') }}'

        const provider = new ethers.providers.Web3Provider(window.ethereum);
        let response = await fetch(loginSignatureUrl);
        const message = await response.text();

        await provider.send("eth_requestAccounts", []);
        const address = await provider.getSigner().getAddress();
        const signature = await provider.getSigner().signMessage(message);

    
        // const balance = await provider.getBalance(address);
        // const balanceOut = ethers.utils.formatEther(balance);
            
        
        //console.log(signature+' and '+address + ' and ============' + balanceOut);

        response = await fetch(loginUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'address': address,
                'signature': signature,
                //'balance': balanceOut,
                '_token': '{{ csrf_token() }}'
            })
            
            
        });
        window.location.href = redirectUrl;
        // const data = await response.text();
        // console.log(data);

    }
</script>