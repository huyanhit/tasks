const apiDomain :string = 'http://aloha.com/api/';
const token: string = '1|x64Wgc7xbuPQtaeipU5FBjTCUO42E5nkwOqW1Ruq0825abee';
export const callApi = async (uri: string, method: string, params: any = null) =>{
    // @ts-ignore
    const response = await fetch(apiDomain + uri, {
        method: method,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Authorization': 'Bearer '+token
        },
        body: params
    });

    return await response.json();
}
