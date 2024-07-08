<?phpnamespace App\Http\Requests;use Illuminate\Foundation\Http\FormRequest;class Image extends FormRequest{    /**     * Determine if the user is authorized to make this request.     *     * @return bool     */    public function authorize()    {        return true;    }    /**     * Get the validation rules that apply to the request.     *     * @return array     */    public function rules()    {        $routeAlias = $this->route()->action['as'];        $rules = [];        if (str_contains($routeAlias, 'admin.upload-image') || str_contains($routeAlias, 'api.upload-image')) {            $rules = [                'image' => 'mimes:jpeg,jpg,png,mp4'            ];        }        if (str_contains($routeAlias, 'api.product.upload-image')) {            $rules = [                'image' => 'mimes:jpeg,jpg,png,mp4,mov,quicktime'            ];        }        if (str_contains($routeAlias, 'admin.logo.store')) {            $rules = [                'images.*' => 'mimes:jpeg,jpg,png,gif|max:10000'            ];        }        if (str_contains($routeAlias, 'admin.slider.store')) {            $rules = [                'images.*' => 'mimes:jpeg,jpg,png,gif|max:10000'            ];        }        if (str_contains($routeAlias, 'admin.products.product-images.store')) {            $rules = [                'images.*' => 'mimes:jpeg,jpg,png,gif|max:10000'            ];        }        return $rules;    }    public function messages()    {        return [            'image' => __('The Image Must be of jpeg,jpg,png,mp4,mov,quicktime'),            'image.mimes' => __('The Image Must be of jpeg,jpg,png,mp4,mov,quicktime'),        ];    }}