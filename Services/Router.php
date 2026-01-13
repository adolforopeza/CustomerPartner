<?php
namespace Aoropeza\CustomerPartner\Services;

use Aoropeza\CustomerPartner\Helper\Config;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Aoropeza\CustomerPartner\Model\CustomerPartnerRepository;

class Router implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;
    protected CustomerPartnerRepository $customerPartnerRepository;
    protected Config $config;

    /**
     * @param ActionFactory $actionFactory
     * @param ResponseInterface $response
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     */
    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        CustomerPartnerRepository $customerPartnerRepository,
        Config $config
    ) {
        $this->config = $config;
        $this->customerPartnerRepository = $customerPartnerRepository;
        $this->actionFactory = $actionFactory;
        $this->response = $response;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    /**
     * Match the request and set the cookie flag.
     *
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(RequestInterface $request)
    {
        // Avoid processing if already handled by this module
        if ($request->getModuleName() === 'customerpartner') {
            return null;
        }

        if (!$this->config->isEnable()) {
            return null;
        }

        $identifier = trim($request->getPathInfo(), '/');

        // Avoid processing empty URLs or those that already have a module assigned
        if (empty($identifier) || $request->getModuleName()) {
            return null;
        }

        $urlModel = $this->customerPartnerRepository->getByUrl($identifier);

        // Verify that the model is valid (not an empty array) and has the necessary data
        if (!is_object($urlModel) || !method_exists($urlModel, 'getUrlKey')) {
            return null;
        }

        if ($urlModel->getUrlKey() && $urlModel->getIsActive()) {
            // --- 1. SET IDENTIFICATION COOKIE ---
            $cookieMetadata = $this->cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setDuration(3600)
                ->setHttpOnly(true)
                ->setPath('/');

            // The cookie value is the customer group ID
            $this->cookieManager->setPublicCookie(
                Config::COOKIE_KEY,
                $urlModel->getCustomerGroupId(),
                $cookieMetadata
            );

            // El valor de la cookie es el ID del grupo de cliente
            $this->cookieManager->setPublicCookie(
                Config::ROUTER_URL_KEY,
                $urlModel->getUrlKey(),
                $cookieMetadata
            );


            // --- 2. REWRITE INTERNAL ROUTE ---
            // This points to the controller that will render the Login/Registration template
            $request->setModuleName(Config::ROUTER_NAME)
                ->setControllerName('index')
                ->setActionName('index')
                ->setParam('url_key', $identifier)
                ->setDispatched(false);

            // Returns an Action\Forward to continue request processing
            return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
        }

        return null;
    }
}
